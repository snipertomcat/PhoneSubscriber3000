<?php

namespace Apithy\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use AWS;

class DumpDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:dump-db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and Upload a compresed Apithy Database Backup';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user=env('DB_USERNAME');
        $password=env('DB_PASSWORD');
        $host=env('DB_HOST');
        $database=env('DB_DATABASE');
        $file_name="apithy-".env('APP_ENV')."-".date('dmY-H:i').".sql";
        $this->info('========== Starting backup of '.env('DB_DATABASE')."============");

        $process = Process::fromShellCommandline('mysqldump -u "$user" --password="$password" -h "$host" "$database" > "$filename"');

        try {
            $process->mustRun(
                null,
                [
                    'user' => $user,
                    'password'=>$password,
                    'host'=>$host,
                    'database'=>$database,
                    'filename'=>$file_name
                ]
            );

            if ($process->isSuccessful()) {
                $this->info('-- Backup of '.env('DB_DATABASE')." generated successfully as $file_name");
                $this->compressDump($file_name);
                $this->uploadFile($file_name);
            }
        } catch (ProcessFailedException $exception) {
            echo $exception->getMessage();
        }
    }

    public function compressDump($file_name)
    {
        $this->info("========== Compressing $file_name ============");
        $process = Process::fromShellCommandline("gzip $file_name");
        try {
            $process->mustRun();

            if ($process->isSuccessful()) {
                $this->info("-- Compression of $file_name finished successfully");
            }
        } catch (ProcessFailedException $exception) {
            echo $exception->getMessage();
        }
    }

    public function uploadFile($file_name)
    {
        $this->info("========== Uploading File $file_name to S3 ============");
        $dump_file=base_path().'/'.$file_name.'.gz';

        $bucket = 'apithy-db-dumps';
        $s3 = AWS::createClient('s3');

        $s3->putObject(array(
            'Bucket'     => $bucket,
            'Key'        => env('APP_ENV') . '/' . $file_name.'.gz',
            'SourceFile' => $dump_file,
        ));

        $this->info("-- File $file_name uploaded successfully");

        $process = Process::fromShellCommandline('rm "$filename"');

        try {
            $process->mustRun(
                null,
                [
                    'filename'=>$dump_file
                ]
            );

            if ($process->isSuccessful()) {
                $this->info("-- File of $file_name deleted successfully");
            }
        } catch (ProcessFailedException $exception) {
            echo $exception->getMessage();
        }
    }
}
