<?php

namespace Apithy\Http\Controllers;

use Apithy\Company;
use Apithy\Events\InvitationCreated;
use Apithy\Http\Responses\WebApiResponse;
use Apithy\Invitation;
use Apithy\Role;
use Apithy\Services\InvitationService;
use Apithy\Utilities\Controllers\Helpers;
use Apithy\Validators\InvitationValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class InvitationsController
 * @package Apithy\Http\Controllers
 */
class InvitationsController extends Controller
{
    use Helpers;

    /**
     * @var \Apithy\Role
     **/
    protected $role;

    private $invitationService;

    /**
     * UsersController constructor.
     *
     * @param \Apithy\Role $role
     * @param Company $company
     * @param \Apithy\Invitation $invitation
     * @param Request $request
     */
    public function __construct(Role $role, Company $company, Request $request)
    {
        parent::__construct();

        $this->role = $role;
        $this->company = $company;
        $this->middleware('auth');
        $this->middleware('active');
        // $this->authorizeResource(Invitation::class);
        $this->invitationService = new InvitationService($request);
        //$this->middleware('super');
    }

    /**
     *
     * @SWG\Get(
     *     path="/users/invitations",
     *     operationId="api.users.invitations",
     *     produces={"application/json"},
     *     tags={"invitations"},
     *     security={ {"passport":{}} },
     *     summary="Return a list of all invitations",
     *     @SWG\Response(response=200, description="successful operation")
     * )
     *
     * Show the application invitations page.
     *
     * @param Request $request
     * @return WebApiResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('fetch', Invitation::class);

        if ($request->filled('page')) {
            return $this->invitationService->getInvitations();
        }

        return view('invitations.index')
            ->with([
                'roles' => $this->role->orderBy('name', 'asc')->allowed()->get(),
                'companies' => $this->company->orderBy('name', 'asc')->allowed()->get(),
                'auth_user' => $request->user()
            ]);
        /*
        return new WebApiResponse([
            'invitations' => $this->invitationService->getInvitations(),
            'roles' => $this->role->orderBy('name', 'asc')->allowed()->get(),
            'companies' => $this->company->orderBy('name', 'asc')->allowed()->get(),
            'auth_user' => $request->user(),
        ], 'invitations.index');
        */
    }

    /**
     *
     * @SWG\Post(
     *     path="/users/invitations",
     *     operationId="api.users.invitation.store",
     *     tags={ "invitations" },
     *     security={ {"passport": {}} },
     *     summary="Store an invitation",
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          description="Invitation data to store",
     *          @SWG\Schema(ref="#/definitions/Invitation"
     *          )
     *      ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Invalid email")
     * )
     *
     * Save a new invitation.
     *
     * @param Request $request
     * @return WebApiResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, InvitationValidator::inviteRules());
        $user = Auth::user();

        $invitation = Invitation::create(array_merge($request->all(), [
            'code' => md5($request->get('email')),
            'user_id' => $user->id,
            'status' => Invitation::INVITATION_PENDING
        ]));

        if ($invitation) {
            flash(trans('messages.invitation_created'), 'success');
            event(new InvitationCreated($invitation));
        }

        return new WebApiResponse(
            [
                'invitation' => $invitation
            ],
            null,
            WebApiResponse::RESPONSE_FOR_STORE,
            null
        );
    }

    public function storeBulkInvitations(Request $request)
    {
        $this->validate($request, InvitationValidator::roleRules());
        return $this->invitationService->storeBulkInvitations();
    }

    /**
     * Resend invitation mail.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend($id)
    {
        return $this->invitationService->resend($id);
    }

    /**
     *
     * @SWG\Delete(
     *     path="/users/invitations/{invitation_id}",
     *     operationId="api.users.invitations.delete",
     *     tags={ "invitations" },
     *     security={ {"passport": {}} },
     *     summary="Delete an invitation",
     *     @SWG\Parameter(
     *          name="invitation_id",
     *          in="path",
     *          description="The invitation's id to delete",
     *          type="integer",
     *          required=true
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=404, description="Invitation not found")
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param Invitation $invitation
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Invitation $invitation)
    {
        return $this->invitationService->destroy($invitation->id);
    }

    public function demo()
    {
        return new WebApiResponse([
            'roles' => $this->role->orderBy('name', 'asc')->allowed()->get(),
            'companies' => $this->company->orderBy('name', 'asc')->allowed()->get(),
            'auth_user' => \Auth::user(),], 'invitations.indexDemo');
    }
}
