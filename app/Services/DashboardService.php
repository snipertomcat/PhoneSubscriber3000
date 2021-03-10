<?php

namespace Apithy\Services;

use Apithy\Company;
use Apithy\Enrollment;
use Apithy\EnrollmentProgress;
use Apithy\EnrollmentTracking;
use Apithy\ExperienceLicence;
use Apithy\Http\Resources\Dashboard\DashboardEnrollmentDetailsResource;
use Apithy\Http\Resources\Dashboard\UserSessionProgressResource;
use Apithy\Taxonomy;
use Apithy\User;
use Apithy\Utilities\Master\Master;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class DashboardService
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getEnrollmentUserDetails()
    {
        try {
            if ($this->request->input('list') == 1) {
                $enrollments = $this->getEnrollments($this->request->input('experience_id'), true);
                return DashboardEnrollmentDetailsResource::collection($enrollments);
            }
            $enrollment = $this->getEnrollment(
                $this->request->input('user_id'),
                $this->request->input('experience_id'),
                true
            );

            $experience = $enrollment->experience;

            $evaluable = false;

            $sessions = $experience->sessions()->with(['activities'])->get();
            for ($i = 0; $i < count($sessions); $i++) {
                if (count($sessions[$i]->activities) > 0) {
                    $evaluable = true;
                    break;
                }
            }

            $enrollment->evaluable_experience = $evaluable;

            return DashboardEnrollmentDetailsResource::make($enrollment);
        } catch (\Exception $ex) {
            //
        }
        return Master::errorResponse('enrollment_details', 'get', 'not_found', 404);
    }

    public function getExperienceEnrollmentUserRecurrence()
    {
        $enrollmentIds = $this->getEnrollmentsIds(
            $this->request->input('user_id'),
            $this->request->input('experience_id')
        );
        $datePeriod = $this->parseDatePeriod($this->request->input('time_period'));
        return $this->getCompanyRecurrence($enrollmentIds, $datePeriod);
    }

    public function getUserExperienceSessionActivities()
    {
        try {
            $enrollment = $this->getEnrollment(
                $this->request->input('user_id'),
                $this->request->input('experience_id')
            );
            $progress = $enrollment->progress()->whereHas('session', function ($query) {
                return $query->where('parent_id', null);
            });

            $this->request->request->add(['enrollment_id' => $enrollment->id]);
            return UserSessionProgressResource::collection($progress->get());
        } catch (\Exception $ex) {
            //
        }
        return Master::errorResponse('evaluation_session_user', 'get', 'not_found', 404);
    }

    private function parseDatePeriod(array $datePeriod = null) : array
    {
        if (!is_array($datePeriod) || empty($datePeriod)) {
            return [];
        }
        if (!isset($datePeriod[0]) && !isset($datePeriod[1])) {
            return [];
        }
        return [
            "{$datePeriod[0]} 00:00:00",
            "{$datePeriod[1]} 23:59:59"
        ];
    }

    private function getEnrollment(int $user_id = null, int $experience_id = null, bool $withCount = false) : Enrollment
    {
        return Enrollment::where([
            ['user_id', $user_id],
            ['experience_id', $experience_id]
        ])
            ->whereHas('user', function ($query) {
                return $query->companyIn(Master::getCompanyId($this->request));
            })
            ->when($withCount, function ($query) {
                return $query->withCount([
                    'progress as sessions_count',
                    'progress as finished_sessions_count' => function ($q) {
                        return $q->where('status', EnrollmentProgress::SESSION_STATUS_FINISHED);
                    }
                ]);
            })
            ->firstOrFail();
    }

    private function getEnrollments(int $experienceId = null, bool $withCount = false): LengthAwarePaginator
    {
        return Enrollment::where('experience_id', $experienceId)
            ->whereHas('user', function ($query) {
                return $query->companyIn(Master::getCompanyId($this->request));
            })
            ->when($this->request->filled('search'), function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->search($this->request->input('search'));
                });
            })
            ->when($withCount, function ($query) {
                return $query->withCount([
                    'progress as sessions_count',
                    'progress as finished_sessions_count' => function ($q) {
                        return $q->where('status', EnrollmentProgress::SESSION_STATUS_FINISHED);
                    }
                ]);
            })->groupBy('user_id')
            ->paginate($this->request->input('per_page', 15));
    }

    private function getEnrollmentsIds(int $user_id = null, int $experience_id = null)
    {
        return Enrollment::whereHas('user', function ($query) {
            return $query->companyIn(Master::getCompanyId($this->request));
        })
            ->when($user_id, function ($query) use ($user_id, $experience_id) {
                return $query->where('user_id', $user_id)
                    ->when($experience_id, function ($q) use ($experience_id) {
                        return $q->where('experience_id', $experience_id);
                    });
            })
            ->when($experience_id && !$user_id, function ($query) use ($experience_id) {
                return $query->where('experience_id', $experience_id)
                    ->whereIn('user_id', $this->getUsersId());
            })
            ->when(!$experience_id && !$user_id, function ($query) {
                return $query->whereIn('user_id', $this->getUsersId());
            })
            ->get()
            ->pluck('id');
    }

    private function getUsersId() : Collection
    {
        return User::whereHas('companies', function ($query) {
            $query->where('id', Master::getCompanyId($this->request));
        })
            ->whereHas('enrollments')
            ->get()
            ->pluck('id');
    }

    private function getCompanyRecurrence(Collection $enrollmentIds, array $dateRange = null) : Collection
    {
        return EnrollmentTracking::where('verb', 'visit')
            ->whereHas('progress', function ($progress) use ($enrollmentIds) {
                $progress->whereHas('enrollment', function ($enrollment) use ($enrollmentIds) {
                    $enrollment->whereIn('id', $enrollmentIds);
                });
            })
            ->when(!empty($dateRange), function ($query) use ($dateRange) {
                return $query->whereBetween('created_at', $dateRange);
            })
            ->get()
            ->groupBy(function ($item) {
                return $created_at = Carbon::parse($item->created_at)->toDateString();
            })
            ->map(function ($items) {
                return [
                    'total' => count($items)
                ];
            })
            ->take(6);
    }

    public function enrollmentGlobals()
    {
        $period = $this->request->get('period', null);
        $company_id = Auth::user()->company_id;
        $tags = $this->request->get('tags', []);

        if ($period) {
            $period = json_decode($period, true);
            $period['start_date'] = Carbon::parse($period['start_date']);
            $period['end_date'] = Carbon::parse($period['end_date']);
        }

        $company_admins = Auth::user()
            ->company()
            ->first()
            ->users()
            ->whereHas('roles', function ($q) {
                $q->where('name', 'like', 'Administrador');
            })
            ->get()
            ->pluck('id');

        if (empty($tags)) {
            $tags = Taxonomy::where('company_id', $company_id)
                ->get();

            if ($tags) {
                $tags_ids = $tags->pluck('id');
                $this->request->merge(['tags' => $tags_ids]);
            }
        }

        $global_data = [
            'total_users' => User::companyIn(Master::getCompanyId($this->request))
                ->when($this->request->filled('tags'), function ($query) {
                    $query->whereHas('taxonomy', function ($query) {
                        $query->whereIn('id', $this->request->get('tags', []));
                    });
                })
                ->when($this->request->filled('period'), function ($query) use ($period) {
                    $query->where('created_at', '>=', $period['start_date'])
                        ->where('created_at', '<=', $period['end_date']);
                })->count(),
            'asigned' => $this->getAsignations(ExperienceLicence::STATUS_UNAVAILABLE)
                ->when($this->request->filled('tags'), function ($query) {
                    $query->whereHas('user', function ($query) {
                        $query->whereHas('taxonomy', function ($query) {
                            $query->whereIn('id', $this->request->get('tags', []));
                        });
                    });
                })->when($this->request->filled('period'), function ($query) use ($period) {
                    $query->where('created_at', '>=', $period['start_date'])
                        ->where('created_at', '<=', $period['end_date']);
                })
                ->count()
            ,
            'finished' => Enrollment::whereHas('user', function ($query) use ($company_admins) {
                $query->whereHas('licences', function ($query) use ($company_admins) {
                    $query->whereIn('buyed_by', $company_admins)->groupBy('experience_id');
                });
            })->when($this->request->filled('tags'), function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->whereHas('taxonomy', function ($query) {
                        $query->whereIn('id', $this->request->get('tags', []));
                    });
                });
            })->when($this->request->filled('period'), function ($query) use ($period) {
                $query->where('finished_at', '>=', $period['start_date'])
                    ->where('finished_at', '<=', $period['end_date']);
            })->where('status', Enrollment::ENROLLMENT_STATUS_FINISHED)->count(),
            'covertion' => 0
        ];

        if ($global_data['asigned']) {
            $global_data["covertion"] = round(((100 / $global_data['asigned']) * $global_data['finished']), 1);
        } else {
            $global_data["covertion"] = 0;
        }

        return $global_data;
    }

    public function enrollmentByTags()
    {
        $company_id = Auth::user()->company_id;
        $tags_ids = $this->request->get('tags', []);

        if (empty($tags_ids)) {
            $tags = Taxonomy::where('company_id', $company_id)
                ->get();

            if ($tags) {
                $tags_ids = $tags->pluck('id');
                $this->request->merge(['tags' => $tags_ids]);
            }
        }

        $tags_data = [];
        $period = $this->request->get('period', null);

        if ($period) {
            if (!is_array($period)) {
                $period = json_decode($period, true);
            }

            $period['start_date'] = Carbon::parse($period['start_date']);
            $period['end_date'] = Carbon::parse($period['end_date']);
        }

        $company_admins = Auth::user()
            ->company()
            ->first()
            ->users()
            ->whereHas('roles', function ($q) {
                $q->where('name', 'like', 'Administrador');
            })
            ->get()
            ->pluck('id');


        foreach ($tags_ids as $tag) {
            $tags_data[$tag] = [];
            $global_data = [
                'name' => Taxonomy::find($tag)->title,
                'total_users' => User::companyIn(Master::getCompanyId($this->request))
                    ->when($this->request->filled('period'), function ($query) use ($period) {
                        $query->where('created_at', '>=', $period['start_date'])
                            ->where('created_at', '<=', $period['end_date']);
                    })
                    ->when($this->request->filled('tags'), function ($query) use ($tag) {
                        $query->whereHas('taxonomy', function ($query) use ($tag) {
                            $query->where('id', $tag);
                        });
                    })
                    ->count(),
                'asigned' => $this->getAsignations(ExperienceLicence::STATUS_UNAVAILABLE)
                    ->when($this->request->filled('period'), function ($query) use ($period) {
                        $query->where('created_at', '>=', $period['start_date'])
                            ->where('created_at', '<=', $period['end_date']);
                    })
                    ->when($this->request->filled('tags'), function ($query) use ($tag) {
                        $query->whereHas('user', function ($query) use ($tag) {
                            $query->whereHas('taxonomy', function ($query) use ($tag) {
                                $query->where('id', $tag);
                            });
                        });
                    })->count(),
                'finished' => Enrollment::whereHas('user', function ($query) use ($company_admins) {
                    $query->whereHas('licences', function ($query) use ($company_admins) {
                        $query->whereIn('buyed_by', $company_admins)->groupBy('experience_id');
                    });
                })->when($this->request->filled('tags'), function ($query) use ($tag) {
                    $query->whereHas('user', function ($query) use ($tag) {
                        $query->whereHas('taxonomy', function ($query) use ($tag) {
                            $query->where('id', $tag);
                        });
                    });
                })->where('status', Enrollment::ENROLLMENT_STATUS_FINISHED)
                    ->when($this->request->filled('period'), function ($query) use ($period) {
                        $query->where('finished_at', '>=', $period['start_date'])
                            ->where('finished_at', '<=', $period['end_date']);
                    })->count(),
                'covertion' => 0
            ];

            if ($global_data['asigned']) {
                $global_data["covertion"] = round((100 / $global_data['asigned']) * $global_data['finished'], 1);
            } else {
                $global_data["covertion"] = 0;
            }

            $tags_data[$tag] = $global_data;
        }

        return $tags_data;
    }

    public function getEnrollmentsDetails()
    {
        $company_id = Auth::user()->company_id;
        $tags_ids = $this->request->get('tags', []);

        if (empty($tags_ids)) {
            $tags = Taxonomy::where('company_id', $company_id)
                ->get();

            if ($tags) {
                $tags_ids = $tags->pluck('id');
                $this->request->merge(['tags' => $tags_ids]);
            }
        }

        $period = $this->request->get('period', null);

        if ($period) {
            if (!is_array($period)) {
                $period = json_decode($period, true);
            }

            $period['start_date'] = Carbon::parse($period['start_date']);
            $period['end_date'] = Carbon::parse($period['end_date']);
        }

        $company_admins = Auth::user()
            ->company()
            ->first()
            ->users()
            ->whereHas('roles', function ($q) {
                $q->where('name', 'like', 'Administrador');
            })
            ->get()
            ->pluck('id');

        $enrollments = ExperienceLicence::BuyedLicenses()
            ->when($this->request->filled('tags'), function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->whereHas('taxonomy', function ($query) {
                        $query->whereIn('id', $this->request->get('tags', []));
                    });
                });
            })->when($this->request->filled('period'), function ($query) use ($period) {
                $query->where('created_at', '>=', $period['start_date'])
                    ->where('created_at', '<=', $period['end_date']);
            })->orderBy('user_id', 'ASC')->get();

        return $enrollments;
    }

    public function parseEnrollmentsExcel($enrollments)
    {
        $enrollments_data = [];
        foreach ($enrollments as $enrollment) {
            $session_time = 0;

            $last_enrollment = Enrollment::where('experience_id', $enrollment->experience_id)
                ->where('user_id', $enrollment->user_id)
                ->latest()
                ->first();

            $last_access="No disponible";
            $score="No disponible";
            $progress=0;
            $finished_at="No disponible";
            $status="Sin comenzar";
            $total_time=0;

            if ($last_enrollment) {
                if ($last_enrollment->finished_at && $last_enrollment->started_at) {
                    $session_time = str_replace(' después', '', (new Carbon($last_enrollment->finished_at))->diffForHumans(new Carbon($last_enrollment->started_at)));
                }

                $user_progress = $last_enrollment->experience->getUserProgress($last_enrollment->user->id, true);
                $last_access_data = $last_enrollment->tracking->where('verb', 'access')->last();
                $last_access = '-';

                if ($last_access_data) {
                    $last_access = $last_access_data->created_at;
                }

                $progress = 0;

                if (!empty($user_progress)) {
                    if (isset($user_progress['progress_percent'])) {
                        $progress = $user_progress['progress_percent'];
                    }
                }

                $score= $last_enrollment->score*100;
                $finished_at=$last_enrollment->finished_at;
                $status= $last_enrollment->getStatusText();
                $total_time=$last_enrollment->getTotalTime();
                if($total_time){
                    $total_time=CarbonInterval::seconds($total_time)->cascade()->forHumans();
                }
            }

            $user_tags = '';
            $user = $enrollment->user;
            $user->load('taxonomy');
            $user_tags_data = $user->taxonomy;

            if (count($user_tags_data)) {
                $user_tags = implode(',', $user_tags_data->pluck('title')->toArray());
            }

            $enrollments_data[] = [
                'personal_code' => ($enrollment->user->personal_code) ? $enrollment->user->personal_code : '-',
                'name' => $enrollment->user->name,
                'surname' => $enrollment->user->surname,
                'access' => $enrollment->user->access,
                'course_name' => $enrollment->experience->title,
                'completion_date' => $finished_at,
                'last_access' => $last_access,
                'status' => $status,
                'final_score' => $score,
                'time' => $total_time,
                'progretion' => $progress.'%',
                'Tags' => $user_tags
            ];
        }

        return $enrollments_data;
    }

    public function getAsignations()
    {
        $licenses = ExperienceLicence::BuyedLicenses();
        return $licenses;
    }

    public function generateExcelReport()
    {
        $enrollments_details = $this->getEnrollmentsDetails();
        $enrollments_db = $this->parseEnrollmentsExcel($enrollments_details);

        $data_tags = $this->enrollmentByTags();
        $spreadsheet = new Spreadsheet();
        $page = 0;

        $tag_num = 1;
        foreach ($data_tags as $tag) {
            $tag_name = str_replace('-', '_', Str::slug($tag['name']));
            $spreadsheet->createSheet();
            //$spreadsheet->setActiveSheetIndex($page + 1);
            $spreadsheet->setActiveSheetIndex($page);
            $spreadsheet->getActiveSheet()->setTitle("general");

            //Table title
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "% De cumplimineto");
            $spreadsheet->getActiveSheet()->mergeCellsByColumnAndRow(1, 1, 2, 1);

            //Table detail
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1, $tag_num + 1, $tag_name);
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, $tag_num + 1, $tag['covertion'])
                ->getColumnDimensionByColumn(1)->setAutoSize(true);
            $tag_num++;
        }

        $options = [
            'title' => 'Comparativo de % cumplimiento',
            'ytitle' => '',
            'chart_name' => 'apithy-chart-' . $tag_name,
            'tags' => $data_tags
        ];


        $chart = $this->generateGeneralGraphic(
            'general',
            $options
        );
        $spreadsheet->getActiveSheet()->addChart($chart);

        $page = 1;
        foreach ($data_tags as $tag) {
            $tag_name = str_replace('-', '_', Str::slug($tag['name']));
            $spreadsheet->createSheet();
            //$spreadsheet->setActiveSheetIndex($page + 1);
            $spreadsheet->setActiveSheetIndex($page);
            $spreadsheet->getActiveSheet()->setTitle($tag_name);

            //Table title
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1, 1, $tag_name);
            $spreadsheet->getActiveSheet()->mergeCellsByColumnAndRow(1, 1, 2, 1);

            //Table detail
            $spreadsheet->getActiveSheet()
                ->setCellValueByColumnAndRow(1, 2, 'Colaboradores Inscritos')
                ->getColumnDimensionByColumn(1)->setAutoSize(true);

            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, 2, $tag['total_users']);

            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1, 3, 'Cursos totales');
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, 3, $tag['asigned']);

            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Cursos terminados');
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, 4, $tag['finished']);

            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1, 5, '% Cumplimiento');
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, 5, $tag['covertion']);

            $options = [
                'title' => $tag['covertion'] . '% de Cumplimiento',
                'ytitle' => '',
                'chart_name' => 'apithy-chart-' . $tag_name,
            ];

            $chart = $this->generateGraphic(
                $tag_name,
                $options
            );

            // Add the chart to the worksheet
            $spreadsheet->getActiveSheet()->addChart($chart);
            $page++;
        }

        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex($page);
        $spreadsheet->getActiveSheet()->setTitle("base_de_datos");
        $spreadsheet->getActiveSheet()->setAutoFilter('A1:L1');

        $headers = [
            'Employ Num',
            'First name',
            'Last name',
            'Access',
            'Course Name',
            'Completion Date',
            'Last Access Date',
            'Status',
            'Final score',
            'Session Time',
            'Course Progression',
            'Tags'
        ];

        foreach ($headers as $column => $header) {
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($column + 1, 1, $header)
                ->getColumnDimensionByColumn($column)->setAutoSize(true);
        }

        $row = 2;
        foreach ($enrollments_db as $enrollment) {
            $column = 1;
            foreach ($enrollment as $enrollment_item) {
                //Index donde conmienzan los tags
                if ($column == 12) {
                    $tags = explode(',', $enrollment_item);

                    foreach ($tags as $index => $tag) {
                        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $tag)
                            ->getColumnDimensionByColumn($column)->setAutoSize(true);

                        $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($column, 1, "Tag-" . $index)
                            ->getColumnDimensionByColumn($column)->setAutoSize(true);

                        $column++;
                    }
                    continue;
                }

                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($column, $row, $enrollment_item)
                    ->getColumnDimensionByColumn($column)->setAutoSize(true);
                $column++;
            }
            $row++;
        }


        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);
        $writer->setOffice2003Compatibility(true);
        $writer->setPreCalculateFormulas(true);
        $response = new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );

        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="acomplishment-report.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    public function generateGeneralGraphic(
        $page_name,
        $options = []
    ) {
        // Set the Labels for each data series we want to plot
        //     Datatype
        //     Cell reference for data
        //     Format Code
        //     Number of datapoints in series
        //     Data values
        //     Data Marker
        $tag_cell = 2;
        $dataSeriesLabels = [];
        foreach ($options['tags'] as $tag) {
            $dataSeriesLabels[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $page_name . '!$A$' . $tag_cell, null, 1);
            $tag_cell++;
        }

        // Set the X-Axis Labels
        //     Datatype
        //     Cell reference for data
        //     Format Code
        //     Number of datapoints in series
        //     Data values
        //     Data Marker
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $page_name . '!$A$2:$A$' . (count($options['tags']) + 1), null, count($options['tags'])), // Q1 to Q4
        ];
        // Set the Data values for each data series we want to plot
        //     Datatype
        //     Cell reference for data
        //     Format Code
        //     Number of datapoints in series
        //     Data values
        //     Data Marker

        $tag_value_cell = 2;
        $dataSeriesValues = [];
        $dataSeriesValues[] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $page_name . '!$B$2:$B$' . (count($options['tags']) + 1), null, count($options['tags']));


        // Build the dataseries
        $series = new DataSeries(
            DataSeries::TYPE_BARCHART, // plotType
            DataSeries::GROUPING_CLUSTERED, // plotGrouping
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues        // plotValues
        );

        // Set additional dataseries parameters
        //     Make it a vertical column rather than a horizontal bar graph
        $series->setPlotDirection(DataSeries::DIRECTION_COL);

        // Set the series in the plot area
        $plotArea = new PlotArea(null, [$series]);
        // Set the chart legend
        $legend = new Legend(Legend::POSITION_RIGHT, null, false);

        $title = new Title($options['title']);
        $yAxisLabel = new Title($options['ytitle']);

        // Create the chart
        $chart = new Chart(
            $options['chart_name'], // name
            $title, // title
            null, // legend
            $plotArea, // plotArea
            true, // plotVisibleOnly
            DataSeries::EMPTY_AS_GAP, // displayBlanksAs
            null, // xAxisLabel
            $yAxisLabel  // yAxisLabel
        );

        // Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition('E1');
        $chart->setBottomRightPosition('P17');


        return $chart;
    }

    public function generateGraphic(
        $page_name,
        $options = []
    ) {
        // Set the Labels for each data series we want to plot
        //     Datatype
        //     Cell reference for data
        //     Format Code
        //     Number of datapoints in series
        //     Data values
        //     Data Marker
        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $page_name . '!$A$2', null, 1), // 2010
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $page_name . '!$A$3', null, 1), // 2010
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $page_name . '!$A$4', null, 1), // 2010
        ];
        // Set the X-Axis Labels
        //     Datatype
        //     Cell reference for data
        //     Format Code
        //     Number of datapoints in series
        //     Data values
        //     Data Marker
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $page_name . '!$A$2:$A$4', null, 3),

        ];
        // Set the Data values for each data series we want to plot
        //     Datatype
        //     Cell reference for data
        //     Format Code
        //     Number of datapoints in series
        //     Data values
        //     Data Marker
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $page_name . '!$B$2:$B$4', null, 1),

        ];

        // Build the dataseries
        $series = new DataSeries(
            DataSeries::TYPE_BARCHART, // plotType
            DataSeries::GROUPING_STANDARD, // plotGrouping
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues        // plotValues
        );

        // Set additional dataseries parameters
        //     Make it a vertical column rather than a horizontal bar graph
        $series->setPlotDirection(DataSeries::DIRECTION_COL);

        // Set the series in the plot area
        $plotArea = new PlotArea(null, [$series]);
        // Set the chart legend
        $legend = new Legend(Legend::POSITION_RIGHT, null, false);

        $title = new Title($options['title']);
        $yAxisLabel = new Title($options['ytitle']);

        // Create the chart
        $chart = new Chart(
            $options['chart_name'], // name
            $title, // title
            null, // legend
            $plotArea, // plotArea
            true, // plotVisibleOnly
            DataSeries::EMPTY_AS_GAP, // displayBlanksAs
            null, // xAxisLabel
            $yAxisLabel  // yAxisLabel
        );

        // Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition('E1');
        $chart->setBottomRightPosition('P17');

        return $chart;
    }
}
