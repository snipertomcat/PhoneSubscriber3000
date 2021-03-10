<?php

namespace Apithy\Services;

use Apithy\Events\InvitationCreated;
use Apithy\Events\InvitationCreatedFromPhone;
use Apithy\Events\InvitationResend;
use Apithy\Events\InvitationResendFromPhone;
use Apithy\Http\Resources\Invitations\InvitationResource;
use Apithy\Invitation;
use Apithy\Utilities\Master\Master;
use Apithy\Validators\InvitationValidator;
use Illuminate\Http\Request;

class InvitationService
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    private function setData($emailPhone, $message = 'Ok', $status = true, $statusMessage = 'No válido')
    {
        return [
            'data' => $emailPhone,
            'message' => $message,
            'status' => $status,
            'status_message' => $statusMessage
        ];
    }

    private function validateEmail($email)
    {
        $validator = validator(['email' => $email], InvitationValidator::emailRule());
        $message = 'ok';
        if ($validator->fails()) {
            $message = $validator->errors()->first('email');
        }
        return $this->setData($email, $message, !$validator->fails());
    }

    private function validatePhone($phone)
    {
        $validator = validator(['phone' => $phone], InvitationValidator::phoneRule());
        $message = 'Ok';
        if ($validator->fails()) {
            $message = $validator->errors()->first('phone');
        }
        return $this->setData($phone, $message, !$validator->fails());
    }

    private function parseData()
    {
        $data = [
            'emails' => [],
            'phones' => [],
            'errors' => []
        ];
        foreach ($this->request->input('email_phone') as $emailPhone) {
            if (Master::hasEmail($emailPhone)) {
                $data['emails'][] = $this->validateEmail($emailPhone);
            } elseif (Master::isPhone($emailPhone)) {
                $data['phones'][] = $this->validatePhone($emailPhone);
            } else {
                $data['errors'][] = $this->setData($emailPhone);
            }
        }
        return $data;
    }

    public function setEmailEvent(Invitation $invitation)
    {
        event(new InvitationCreated($invitation));
    }

    public function setPhoneEvent(Invitation $invitation)
    {
        event(new InvitationCreatedFromPhone($invitation));
    }

    public function resendEmailEvent(Invitation $invitation)
    {
        event(new InvitationResend($invitation));
    }

    public function resendPhoneEvent(Invitation $invitation)
    {
        event(new InvitationResendFromPhone($invitation));
    }

    public function saveInvitation($emailPhone, $isPhone = false)
    {
        $company_id = $this->request->input('company_id');
        if (!$this->request->user()->isSuper()) {
            $company_id = Master::getCompanyId($this->request);
        }
        $data = [
            'role_id' => $this->request->input('role_id'),
            'company_id' => $company_id,
            'contact' => $emailPhone,
            'code' => md5($emailPhone),
            'user_id' => $this->request->user()->id,
            'status' => Invitation::INVITATION_PENDING
        ];
        $invitation = new Invitation();
        $invitation->fill($data);
        $invitation->save();

        if ($invitation) {
            if (!$isPhone) {
                $this->setEmailEvent($invitation);
            } else {
                $this->setPhoneEvent($invitation);
            }
        }
        return $invitation;
    }

    public function storeBulkInvitations()
    {
        $data = $this->parseData();
        foreach ($data['emails'] as $key => &$email) {
            if ($email['status']) {
                $invitation = $this->saveInvitation($email['data']);
                if (!$invitation) {
                    $email['status'] = false;
                    $email['message'] = 'No se pudo enviar la invitación.';
                    $email['status_message'] = 'No enviado.';
                } else {
                    $email['status'] = true;
                    $email['message'] = 'Invitación enviada correctamente.';
                    $email['status_message'] = 'Enviado.';
                }
            }
        }
        foreach ($data['phones'] as $key => &$phone) {
            if ($phone['status']) {
                $invitation = $this->saveInvitation($phone['data'], true);
                if (!$invitation) {
                    $phone['status'] = false;
                    $phone['message'] = 'No se pudo enviar la invitación.';
                    $phone['status_message'] = 'No enviado.';
                } else {
                    $phone['status'] = false;
                    $phone['message'] = 'Invitación enviada correctamente.';
                    $phone['status_message'] = 'Enviado';
                }
            }
        }
        return Master::successResponse($data);
    }

    public function getInvitations()
    {
        $relations = ['role', 'company', 'user'];

        if ($this->request->filled('load')) {
            $relations = array_merge($relations, $this->request->input('load'));
        }
        return InvitationResource::collection(
            listing(Invitation::class, $relations, $this->request->input('per_page'))
        );
    }

    public function resend($id)
    {
        try {
            $invitation = Invitation::where([
                ['id', $id],
                ['status', Invitation::INVITATION_PENDING]
            ])
                ->firstOrFail();
            if (Master::isPhone($invitation->contact)) {
                $this->resendPhoneEvent($invitation);
            } else {
                $this->resendEmailEvent($invitation);
            }
            return InvitationResource::make($invitation);
        } catch (\Exception $ex) {
            // return Master::exceptionResponse('invitation_resend', ['e' => $ex->getMessage()]);
        }
        return Master::errorResponse(
            'invitation_resend',
            'resend',
            'resend',
            400,
            ['message' => 'Esta invitación ya ha sido aceptada']
        );
    }

    public function destroy($id)
    {
        try {
            $invitation = Invitation::where([
                ['id', $id],
                ['status', 0]
            ])
                ->firstOrFail();
            $invitation->delete();
            return Master::successResponse();
        } catch (\Exception $ex) {
            // return Master::exceptionResponse('invitation_destroy', ['e' => $ex->getMessage()]);
        }
        return Master::errorResponse('invitation_destroy', 'delete');
    }
}
