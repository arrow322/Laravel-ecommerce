<?php

namespace Webkul\Customer\Http\Controllers;

use Webkul\Product\Repositories\ProductRepository;
use Webkul\Customer\Repositories\WishlistRepository;
use Cart;

/**
 * Customer controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class WishlistController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * ProductRepository object
     *
     * @var Object
    */
    protected $wishlistRepository;

    /**
     * WishlistRepository object
     *
     * @var Object
    */
    protected $productRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Customer\Repositories\WishlistRepository $wishlistRepository
     * @param  \Webkul\Product\Repositories\ProductRepository   $productRepository
     * @return void
     */
    public function __construct(
        WishlistRepository $wishlistRepository,
        ProductRepository $productRepository
    )
    {
        $this->middleware('customer');

        $this->_config = request('_config');

        $this->wishlistRepository = $wishlistRepository;

        $this->productRepository = $productRepository;
    }

    /**
     * Displays the listing resources if the customer having items in wishlist.
     */
    public function index()
    {
        $wishlistItems = $this->wishlistRepository->findWhere([
            'channel_id' => core()->getCurrentChannel()->id,
            'customer_id' => auth()->guard('customer')->user()->id]
        );

        return view($this->_config['view'])->with('items', $wishlistItems);
    }

    /**
     * Function to add item to the wishlist.
     *
     * @param integer $itemId
     */
    public function add($itemId)
    {
        $product = $this->productRepository->findOneByField('id', $itemId);

        if (! $product->status) {
            return redirect()->back();
        }

        $data = [
            'channel_id' => core()->getCurrentChannel()->id,
            'product_id' => $itemId,
            'customer_id' => auth()->guard('customer')->user()->id
        ];

        $checked = $this->wishlistRepository->findWhere([
                'channel_id' => core()->getCurrentChannel()->id,
                'product_id' => $itemId,
                'customer_id' => auth()->guard('customer')->user()->id
            ]);

        //accidental case if some one adds id of the product in the anchor tag amd gives id of a variant.
        if ($product->parent_id != null) {
            $product = $this->productRepository->findOneByField('id', $product->parent_id);
            $data['product_id'] = $product->id;
        }

        if ($checked->isEmpty()) {
            if ($this->wishlistRepository->create($data)) {
                session()->flash('success', trans('customer::app.wishlist.success'));

                return redirect()->back();
            } else {
                session()->flash('error', trans('customer::app.wishlist.failure'));

                return redirect()->back();
            }
        } else {
            session()->flash('warning', trans('customer::app.wishlist.already'));

            return redirect()->back();
        }
    }

    /**
     * Function to remove item to the wishlist.
     *
     * @param integer $itemId
     */
    public function remove($itemId)
    {
        $customerWishlistItems = auth()->guard('customer')->user()->wishlist_items;

        foreach ($customerWishlistItems as $customerWishlistItem) {
            if ($itemId == $customerWishlistItem->id) {
                $this->wishlistRepository->delete($itemId);

                session()->flash('success', trans('customer::app.wishlist.removed'));

                return redirect()->back();
            }
        }

        session()->flash('error', trans('customer::app.wishlist.remove-fail'));

        return redirect()->back();
    }

    /**
     * Function to move item from wishlist to cart.
     *
     * @param integer $itemId
     */
    public function move($itemId)
    {
        $wishlistItem = $this->wishlistRepository->findOneByField('id', $itemId);

        if (! isset($wishlistItem) || $wishlistItem->customer_id != auth()->guard('customer')->user()->id) {
            session()->flash('warning', trans('shop::app.security-warning'));

            return redirect()->route('customer.wishlist.index');
        }

        try {
            $result = Cart::moveToCart($wishlistItem);
        } catch (\Exception $e) {
            session()->flash('warning', $e->getMessage());

            return redirect()->back();
        }

        if ($result) {
            if ($wishlistItem->delete()) {
                session()->flash('success', trans('shop::app.wishlist.moved'));

                Cart::collectTotals();

                return redirect()->back();
            } else {
                session()->flash('error', trans('shop::app.wishlist.move-error'));

                return redirect()->back();
            }
        } else {
            session()->flash('info', trans('shop::app.wishlist.option-missing'));

            return redirect()->route('shop.products.index', $wishlistItem->product->url_key);
        }
    }

    /**
     * Function to remove all of the items items in the customer's wishlist
     *
     * @return Mixed Response & Boolean
     */
    public function removeAll()
    {
        $wishlistItems = auth()->guard('customer')->user()->wishlist_items;

        if ($wishlistItems->count() > 0) {
            foreach ($wishlistItems as $wishlistItem) {
                $this->wishlistRepository->delete($wishlistItem->id);
            }
        }

        session()->flash('success', trans('customer::app.wishlist.remove-all-success'));

        return redirect()->back();
    }
}
