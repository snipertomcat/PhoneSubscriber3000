<?php

namespace Apithy;

use Apithy\Utilities\Model\Filterable;
use Apithy\Utilities\Model\FilterTrait;
use Apithy\Utilities\Model\Sortable;
use Apithy\Utilities\Model\SortTrait;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;
use Stripe\Charge as StripeCharge;
use Stripe\Error\Card;
use Stripe\Order as StripeOrder;
use Stripe\Token as StripeToken;
use Stripe\SKU as StripeSku;
use Stripe\Product as StripeProduct;
use Stripe\Card as StripeCard;
use Stripe\Stripe;

/**
 * Class Country
 * @package Apithy
 */
class PaymentInformation extends Model implements Sortable, Filterable
{
    use SortTrait, FilterTrait, Billable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_payment_information';

    /**
     * The database table timestamps not enabled.
     *
     * @var boolean
     */
    public $timestamps = false;

    protected $appends = [
        'card_type_icon'
    ];


    /**
     * Return the user of this PaymentInformation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Return full path cover.
     *
     * @return string
     */
    public function getCardTypeIconAttribute()
    {
        if ($this->card_brand == "visa" || $this->card_brand == "VISA" || $this->card_brand == "Visa") {
            return "fa-cc-" . strtolower($this->card_brand);
        }

        if ($this->card_brand == "MC" ||
            $this->card_brand == "mc" ||
            $this->card_brand == "mastercard" ||
            $this->card_brand == "master-card" ||
            $this->card_brand == "MASTERCARD" ||
            $this->card_brand == "MASTER-CARD" ||
            $this->card_brand == "MasterCard"
        ) {
            return "fa-cc-mastercard";
        }

        if ($this->card_brand == "amex" ||
            $this->card_brand == "americanexpress" ||
            $this->card_brand == "american-express" ||
            $this->card_brand == "American Express"
        ) {
            return "fa-cc-amex";
        }
    }

    public function deleteCard($card_id)
    {
        $this->removePaymentMethod($card_id);
        return true;
    }

    public function cardExist($newCard)
    {
        $exists=false;
        $this->cards()->each(function ($card) use ($newCard, $exists) {
            if ($card->brand == $newCard->brand && $card->last4 == $newCard->last4) {
                $exists = true;
            }
        });

        return $exists;
    }

    public function setAsDefaultCard()
    {
        $customer=$this->asStripeCustomer();
        $customer->default_source=$this->payment_source_id;
        $customer->save();
    }



    protected function generateOrderData($experience_data, $customer_id)
    {
        if (!is_array($experience_data)) {
            $experiences = [];
            $experiences[] = $experience_data;
        } else {
            $experiences = $experience_data;
        }

        $order_data = [
            'currency' => 'MXN',
            'customer' => $customer_id,
            'items' => [],
        ];

        $lineItems = [];

        $StripeProducts = StripeProduct::all();
        $StripeSkus=StripeSku::all();

        foreach ($experiences as $index => $experience) {
            $sku = $this->createSku($experience, $StripeProducts, $StripeSkus);

            $lineItems[$index] =
                [
                    'description' => $experience->summary,
                    'amount' => ($experience->price * 100) * $experience->quantity,
                    'parent' => $sku->id,
                    'quantity' => $experience->quantity,
                    'type' => 'sku',
                    'currency' => 'MXN',
                ];
        }

        $order_data['items'] = $lineItems;


        return $order_data;
    }

    public function createOrder($experiences, array $options = [])
    {
        if (!$this->hasStripeId()) {
            throw new InvalidArgumentException('No payment source provided.');
        }

        Stripe::setApiKey($this->getStripeKey());

        $order = $this->generateOrderData($experiences, $this->stripe_id);
        $amount_total = 0;

        foreach ($experiences as $index => $experience) {
            $amount_total += ($experience->price * 100) * $experience->quantity;
        }

        try {
            $order = StripeOrder::create($order, $options);

            foreach ($experiences as $index => $experience) {
                if (isset($experience->cartAttributes)) {
                    foreach ($order['items'] as $orderItem) {
                        if ($orderItem['parent'] == 'experience_' . $experience->system_id) {
                            $orderItem->metadata = [];
                            $orderItem->metadata['quantity'] = $experience->quantity;
                            $orderItem->metadata['experience_id'] = $experience->id;
                            $orderItem->metadata['description'] = $experience->description;
                            $orderItem->metadata['inherit_users'] = $experience->cartAttributes->inherit_users;
                            $orderItem->metadata['corporative_use'] = $experience->cartAttributes->corporative_use;
                            $orderItem->metadata['personal_use'] = $experience->cartAttributes->personal_use;
                            $orderItem->metadata['company_areas'] = $experience->cartAttributes->company_areas;
                            $orderItem->metadata['company_positions'] = $experience->cartAttributes->company_positions;
                            $orderItem->metadata['company_users'] = $experience->cartAttributes->company_users;
                            $orderItem->metadata['new_users'] = $experience->cartAttributes->new_users;
                            $orderItem->metadata['licences_number'] = $experience->cartAttributes->licences_number;
                            $orderItem->metadata['licences_to_buy'] = $experience->cartAttributes->licences_to_buy;
                            $orderItem->metadata['free_licences'] = $experience->cartAttributes->free_licences;
                            $orderItem->metadata['assignation_id'] = $experience->cartAttributes->assignation_id;
                        }
                    }
                }
            }


            $charge = [
                'amount' => $amount_total,
                'order' => $order->id,
                'description' => 'Experience Purchase',
                'currency' => 'MXN',
                'customer' => $this->stripe_id,
                //'payment_method'=>$this->payment_source_id
            ];

            $response = StripeCharge::create($charge, $options);
            $response->order = $order;

            $response;
        } catch (Card $e) {
            throw $e;
        }

        return $response;
    }

    public function createSku($experience, $StripeProducts, $stripeSkus)
    {
        if (!$this->hasStripeId()) {
            throw new InvalidArgumentException('No payment source provided.');
        }

        Stripe::setApiKey($this->getStripeKey());

        $stripeProductIds = [];

        $product_data = [
            'id' => 'experience_' . $experience->system_id,
            'name' => $experience->title,
            'type' => 'good',
            'shippable' => false
        ];


        foreach ($StripeProducts->data as $StripeProduct) {
            $stripeProductIds[] = $StripeProduct['id'];
        }

        $product_exist = in_array('experience_' . $experience->system_id, $stripeProductIds);

        if (!$product_exist) {
            //$product = StripeProduct::create($product_data);
            //$productId = $product->id;

        } else {
            $productId = 'experience_' . $experience->system_id;
        }

        $productId = 'experience_' . $experience->system_id;

        $skuItem = [
            'id' => 'experience_' . $experience->system_id,
            'product' => $productId,
            'price' => $experience->price * 100,
            "currency" => "MXN",
            "inventory" => [
                "type" => "infinite",
            ]
        ];

        $stripeSkuIds = [];

        foreach ($stripeSkus->data as $StripeSku) {
            $stripeSkuIds[] = $StripeSku['id'];
        }

        $sku_exist = in_array('experience_' . $experience->system_id, $stripeSkuIds);

        if (!$sku_exist) {
            $sku = StripeSku::create($skuItem);
            return $sku;
        } else {
            $sku = StripeSku::retrieve('experience_' . $experience->system_id);
        }

        return $sku;
    }

    public function updateCard($token)
    {
        $customer = $this->asStripeCustomer();

        $token = StripeToken::retrieve($token, ['api_key' => $this->getStripeKey()]);

        // If the given token already has the card as their default source, we can just
        // bail out of the method now. We don't need to keep adding the same card to
        // a model's account every time we go through this particular method call.
        if ($token[$token->type]->id === $customer->default_source) {
            return;
        }

        $card = $customer->sources->create(['source' => $token]);

        $customer->default_source = $card->id;

        $customer->save();

        // Next we will get the default source for this model so we can update the last
        // four digits and the card brand on the record in the database. This allows
        // us to display the information on the front-end when updating the cards.
        $source = $customer->default_source
            ? $customer->sources->retrieve($customer->default_source)
            : null;

        $this->fillCardDetails($source);

        $this->save();
    }

    protected function fillCardDetails($card)
    {
        if ($card instanceof StripeCard) {
            $this->card_brand = $card->brand;
            $this->card_last_four = $card->last4;
        } elseif ($card instanceof StripeBankAccount) {
            $this->card_brand = 'Bank Account';
            $this->card_last_four = $card->last4;
        }

        return $this;
    }

    public function getStripeKey()
    {
        return getenv('STRIPE_SECRET');
    }
}
