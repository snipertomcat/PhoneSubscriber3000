<?php

namespace Apithy\Http\Controllers;

use Apithy\Company;
use Apithy\Experience;
use Apithy\Http\Controllers\Controller as Controller;
use Apithy\Http\Responses\WebApiResponse;
use Apithy\Session;
use Apithy\User;
use Apithy\Utilities\Controllers\Helpers;
use Darryldecode\Cart\Cart;
use Apithy\Services\BillingsService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ShoppingCartController
 * @property Session session
 * @package Apithy\Http\Controllers
 */
class ShoppingCartController extends Controller
{
    use Helpers;

    /**
     * @var \Apithy\experience
     */
    protected $experience;


    /**
     * @var \Apithy\Company
     */
    protected $company;

    /**
     * @var \Apithy\User
     */
    protected $user;

    /**
     * @var \Apithy\Services\BillingsService
     */
    protected $billing_service;

    const BUY_LIMIT = 100000;

    /**
     * ShoppingCartController constructor.
     */

    public function __construct(
        Company $company,
        User $user,
        Experience $experience,
        BillingsService $billing_service
    ) {
        parent::__construct();

        $this->company = $company;
        $this->user = $user;
        $this->experience = $experience;
        $this->billing_service = $billing_service;

        $this->middleware('auth');
        $this->middleware('active');
        //$this->authorizeResource(Session::class);
    }

    /**
     * @SWG\Get(
     *     path="/shopping-cart/",
     *     operationId="api.shopping.card.index",
     *     produces={"application/json"},
     *     tags={"shopping-cart"},
     *     security={ {"passport":{}} },
     *     summary="Return a list of Session",
     *     @SWG\Parameter(
     *         name="shopping-cart",
     *         in="path",
     *         type="number",
     *         required=true
     *     ),
     *     @SWG\Response(response=200, description="Successful operation")
     * )
     *
     * Display a listing of the resource.
     *
     * @param Experience $experience
     * @return WebApiResponse
     */
    public function index()
    {
        //$this->authorize('fetch', session::class);
        $userId = auth()->user()->id;

        return new WebApiResponse([
            'cart' => Cart::session($userId)->getContent(),
        ], false);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Experience $experience
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function add(Experience $experience, Request $request)
    {
        $userId = auth()->user()->id; // or any string represents user identifier

        if ($request->delete_before_add) {
            \Cart::session($userId)->remove($experience->id);
        }

        \Cart::session($userId)->add(
            $experience->id,
            $experience->title,
            $experience->price,
            $request->get('quantity', 1),
            [
                'cover' => $experience->full_path_cover,
                'type' => $experience->type,
                'sessions' => ($experience->type == Experience::TYPE_JOURNEY) ?
                    count($experience->adventures) : count($experience->sessions),
                'use_for' => ($request->get('corporative_use', false)) ? 'Corporativo' : 'Personal',
                'personal_use' => ($request->get('corporative_use', false)) ? false : true,
                'corporative_use' => $request->get('corporative_use', false),
                'system_id' => $experience->system_id,
                'company_areas' => $request->get('company_areas', []),
                'company_positions' => $request->get('company_positions', []),
                'company_tags' => $request->get('company_tags', []),
                'company_users' => $request->get('company_users', []),
                'new_users' => $request->get('new_users', []),
                'inherit_users' => $request->get('inherit_users', []),
                'free_licences' => $request->get('free_licences', false),
                'licences_number' => $request->get('licences_number', false),
                'licences_to_buy' => $request->get('licences_to_buy', false)
            ]
        );
        $cart_content = \Cart::session($userId)->getContent();
        //unset($cart_content[$experience->id]->attributes['inherit_users']);
        //Cookie::queue(\Cookie::make('cart_list', $cart_content, 0, null, null, false, false));

        return new JsonResponse(['card' => \Cart::session($userId)->getContent()], 200);
    }

    /**
     * @SWG\Delete(
     *     path="/shopping-cart/{experience}",
     *     operationId="api.shopping-cart.delete",
     *     produces={"application/json"},
     *     tags={"shopping-cart"},
     *     security={ {"passport":{}} },
     *     summary="Return a shopping-cart",
     *     @SWG\Parameter(
     *         name="experience",
     *         in="path",
     *         type="number",
     *         required=true
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=404, description="Session not found")
     * )
     *
     * Display the specified resource.
     *
     * @param Experience $experience
     * @return WebApiResponse
     */

    public function remove(Experience $experience, Request $request)
    {

        $userId = auth()->user()->id;
        \Cart::session($userId)->remove($experience->id);

        $cart_items = \Cart::session($userId)->getContent();

        $cart = [
            'items' => $cart_items,
            'total_quantity' => \Cart::session($userId)->getTotalQuantity(),
            'subtotal' => \Cart::session($userId)->getSubTotal(),
            'total' => \Cart::session($userId)->getTotal()
        ];
        $cart_content = \Cart::session($userId)->getContent();
        \Cookie::queue(\Cookie::make('cart_list', $cart_content, 0, null, null, false, false));

        return new JsonResponse(['cart' => (object)$cart], 200);
    }

    public function checkout()
    {
        $template = 'student.checkout.index';

        $user = auth()->user();
        $user->load(['paymentInformation' => function ($query) {
            $query->where('status', 1);
        }]);



        $cart_items = \Cart::session($user->id)->getContent();

        $cart = [
            'items' => $cart_items,
            'total_quantity' => \Cart::session($user->id)->getTotalQuantity(),
            'subtotal' => \Cart::session($user->id)->getSubTotal(),
            'total' => \Cart::session($user->id)->getTotal()
        ];


        return new WebApiResponse([
            'cart' => (object)$cart,
            'user' => $user
        ], $template);
    }
}
