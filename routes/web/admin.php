<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\LockupController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\VariationController;
use App\Http\Controllers\LockupTypeController;
use App\Http\Controllers\ManageRoleController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\ParentPackageController;
use App\Http\Controllers\VariationTypeController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SpecialOccasionController;

Route::group(
    [
        'middleware' => ['auth'],

    ],
    function () {
        #admin profile route
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/profile', [HomeController::class, 'profile'])->name('admin.profile');
        Route::post('/profile/update', [HomeController::class, 'updateProfile'])->name('admin.updateProfile');
        Route::post('/profile/update-profile-image', [HomeController::class, 'updateProfileImage'])->name('admin.updateProfileImage');
        Route::get('/change-password', [HomeController::class, 'changePassword'])->name('admin.changePassword');
        Route::post('/change-password/store', [HomeController::class, 'changePasswordStore'])->name('admin.changePasswordStore');
        Route::post('/notification', [HomeController::class, 'newOrderNotification'])->name('admin.newOrderNotification');
        Route::post('/mark-as-read', [HomeController::class, 'markNotification'])->name('admin.markNotification');
        Route::get('/change-language/{lang}',[HomeController::class, 'changeLanguage'])->name('admin.changeLanguage');

        Route::group(['prefix' => 'users',], function () {

            Route::group(['prefix' => 'admin',], function () {
                Route::get('/', [AdminController::class, 'adminList'])->name('admin.adminList');
                Route::get('/create', [AdminController::class, 'createAdmin'])->name('admin.createAdmin');
                Route::post('/store', [AdminController::class, 'storeAdmin'])->name('admin.storeAdmin');
                Route::get('/view/{uuid}', [AdminController::class, 'viewAdmin'])->name('admin.viewAdmin');
                Route::get('/edit/{uuid}', [AdminController::class, 'editAdmin'])->name('admin.editAdmin');
                Route::post('/edit/{uuid}', [AdminController::class, 'updateAdmin'])->name('admin.updateAdmin');
                Route::post('/image-upload/{uuid}', [AdminController::class, 'updateImage'])->name('admin.updateImage');
                Route::delete('/delete/{id}', [AdminController::class, 'destroyAdmin'])->name('admin.destroyAdmin');
            });

             #subscription route
             Route::get('/subscription',[SubscriptionController::class,'subscriptionList'])->name('admin.subscription');
             Route::get('/subscriptioncreate',[SubscriptionController::class,'create'])->name('admin.subscriptionCreate');
             Route::post('/subscriptionstore',[SubscriptionController::class,'store'])->name('admin.subscriptionStore');
             Route::get('/subscriptionview/{id}',[SubscriptionController::class,'view'])->name('admin.subscriptionView');
             Route::get('/subscriptionedit/{id}',[SubscriptionController::class,'edit'])->name('admin.subscriptionEdit');
             Route::post('/subscriptionupdate/{id}',[SubscriptionController::class,'update'])->name('admin.subscriptionUpdate');
             Route::post('/subscriptiondestroy/{id}',[SubscriptionController::class,'destroy'])->name('admin.subscriptionDestroy');

            #admin user route
                Route::get('/', [ManageUserController::class, 'usersList'])->name('admin.usersList');
                Route::get('/view/{uuid}', [ManageUserController::class, 'viewUser'])->name('admin.viewUser');
                Route::get('/edit/{uuid}', [ManageUserController::class, 'editUser'])->name('admin.editUser');
                Route::post('/user-image/{uuid}', [ManageUserController::class, 'updateUserImage'])->name('admin.updateUserImage');
                Route::post('/edit/{uuid}', [ManageUserController::class, 'updateUser'])->name('admin.updateUser');
                Route::delete('/delete/{id}', [ManageUserController::class, 'destroyUser'])->name('admin.destroyUser');
                Route::get('/deleted-user-info', [ManageUserController::class, 'deletedUserInfo'])->name('admin.deletedUserInfo');
                Route::post('/restore-user', [ManageUserController::class, 'restoreDeletedUser'])->name('admin.restoreDeletedUser');
                Route::delete('/force-delete-user/{id}', [ManageUserController::class, 'forceDeleteUser'])->name('admin.forceDeleteUser');

            #admin role route
            Route::group(['prefix' => 'role',], function () {
                Route::get('/', [ManageRoleController::class, 'index'])->name('admin.roleList');
                Route::get('/create', [ManageRoleController::class, 'create'])->name('admin.createRole');
                Route::post('/store', [ManageRoleController::class, 'store'])->name('admin.storeRole');
                Route::get('/show/{id}', [ManageRoleController::class, 'show'])->name('admin.showRole');
                Route::get('/edit/{id}', [ManageRoleController::class, 'edit'])->name('admin.editRole');
                Route::post('/update/{id}', [ManageRoleController::class, 'update'])->name('admin.updateRole');
                Route::post('/destroy/{id}', [ManageRoleController::class, 'destroy'])->name('admin.destroyRole');
            });
        });
        #Catalog route
        Route::group(['prefix' => 'catalog'], function () {
            #Catalog/Products route
            Route::group(['prefix' => 'products',], function () {

                Route::get('/', [ProductController::class, 'productIndex'])->name('admin.productList');
                Route::get('/create', [ProductController::class, 'productCreate'])->name('admin.createProduct');
                Route::get('/productAttribute/{id}', [ProductController::class, 'displayAttribute'])->name('admin.displayAttributeProduct');
                Route::get('/productCategory', [ProductController::class, 'displayCategory'])->name('admin.displayCategoryProduct');
                Route::post('/store', [ProductController::class, 'productStore'])->name('admin.storeProduct');
                Route::get('/view/{id}', [ProductController::class, 'productView'])->name('admin.viewProduct');
                Route::get('/edit/{id}', [ProductController::class, 'productedit'])->name('admin.editProduct');
                Route::get('/productAttributeData/{id}', [ProductController::class, 'displayAttributeData'])->name('admin.displayAttributeDataProduct');
                Route::post('/update/{id}', [ProductController::class, 'productupdate'])->name('admin.updateProduct');
                Route::get('/delete/{id}', [ProductController::class, 'productDestroy'])->name('admin.destroyProduct');
                Route::get('/product/download', [ProductController::class, 'download'])->name('admin.product.download');

                Route::post('/variation-value',[ProductController::class, 'variationValue'])->name('admin.variationValue');
            });
            #Catalog/Catogries route
            Route::group(['prefix' => 'categories',], function () {
                Route::get('/', [CategoryController::class, 'indexCategory'])->name('admin.categoriesList');
                Route::post('/store', [CategoryController::class, 'storeCategory'])->name('admin.storeCategory');
                Route::get('/view/{id}', [CategoryController::class, 'viewCategory'])->name('admin.viewCategory');
                Route::get('/edit/{id}', [CategoryController::class, 'editCategory'])->name('admin.editCategory');
                Route::post('/update/{id}', [CategoryController::class, 'updateCategory'])->name('admin.updateCategory');
                Route::delete('/delete/{id}', [CategoryController::class, 'destroyCategory'])->name('admin.destroyCategory');
            });
        });

        #sales route
        Route::group(['prefix' => 'sale'], function () {
            #Order route
            Route::group(['prefix' => 'order'], function () {
            Route::get('/', [OrdersController::class, 'adminOrderIndex'])->name('admin.allOrderList');
            Route::get('/package', [OrdersController::class, 'packageList'])->name('admin.packageList');
            Route::get('/item-details/{id}', [OrdersController::class, 'adminOrderItemDetails'])->name('admin.orderItemDetails');
            Route::post('/change-order-status/{id}', [OrdersController::class, 'changeOrderStatus'])->name('admin.changeOrderStatus');
            Route::post('/change-payment-status', [OrdersController::class, 'changePaymentStatus'])->name('admin.changePaymentStatus');
            Route::delete('/delete/{id}', [OrdersController::class, 'destroyPackage'])->name('admin.destroyPackage');
            });
        });

        #admin stores  route
        Route::group(['prefix' => 'stores'], function () {
           #admin stores/attributes  route
           Route::group(['prefix' => 'attributes',], function () {
               #admin stores/attributes/Product  route
               Route::group(['prefix' => 'product',], function () {

               Route::get('/', [AttributeController::class, 'attributeIndex'])->name('admin.attributeProductList');
               Route::get('/create', [AttributeController::class, 'attributeCreate'])->name('admin.createProductAttribute');
               Route::post('/store', [AttributeController::class, 'attributeStore'])->name('admin.storeProductAttribute');
               Route::get('/edit/{id}', [AttributeController::class, 'attributeEdit'])->name('admin.editProductAttribute');
               Route::post('/update/{id}', [AttributeController::class, 'attributeUpdate'])->name('admin.updateProductAttribute');
               Route::delete('/delete/{id}', [AttributeController::class, 'attributeDestroy'])->name('admin.destroyProductAttribute');
               });
               #admin stores/attributes/attributeSet  route
               Route::group(['prefix' => 'attributeSet',], function () {

                Route::get('variation-type', [VariationTypeController::class, 'index'])->name('admin.indexVariationType');
                Route::get('variation-type/{id}', [VariationTypeController::class, 'show'])->name('admin.showVariationType');
                Route::post('variation-type/store', [VariationTypeController::class, 'store'])->name('admin.storeVariationType');
                Route::post('variation-type/update/{id}', [VariationTypeController::class, 'update'])->name('admin.updateVariationType');
                Route::post('variation-item/store', [VariationController::class, 'store'])->name('admin.storeVariationItem');
                Route::post('variation-item/update/{id}', [VariationController::class, 'update'])->name('admin.updateVariationItem');
                Route::post('variation-item/destroy/{id}', [VariationController::class, 'destroy'])->name('admin.destroyVariationItem');
               });
           });
        });

        #admin stores  route
        Route::group(['prefix' => 'reports'], function () {
           #admin stores/attributes  route
           Route::group(['prefix' => 'orders',], function () {
               Route::get('/order-report', [ReportController::class, 'orderReportList'])->name('admin.orderReportList');
               Route::get('/order/download', [ReportController::class, 'downloadOrderReport'])->name('admin.order.download');
           });
        });

        #Marketing Route
        Route::group(['prefix' => 'marketing'], function () {
            #Marketing/promotion
            Route::group(['prefix' => 'promotion'], function () {
                Route::group(['prefix' => 'coupon'], function () {
                    Route::get('/', [CouponController::class, 'couponIndex'])->name('admin.couponList');
                    Route::get('/view/{id}', [CouponController::class, 'viewCoupon'])->name('admin.viewCoupon');
                    Route::get('/create', [CouponController::class, 'createCoupon'])->name('admin.createCoupon');
                    Route::post('/store', [CouponController::class, 'storeCoupon'])->name('admin.storeCoupon');
                    Route::get('/edit/{id}', [CouponController::class, 'editCoupon'])->name('admin.editCoupon');
                    Route::post('/update/{id}', [CouponController::class, 'updateCoupon'])->name('admin.updateCoupon');
                    Route::delete('/delete/{id}', [CouponController::class, 'destroyCoupon'])->name('admin.destroyCoupon');
                });
            });
            // #Marketing/User Content
            // Route::group(['prefix' => 'user_content', 'middleware' => ['checkLicense']], function () {
            // #Marketing/User Content/All Review
            // Route::group(['prefix' => 'all_review'], function () {
            //     Route::get('/', [RatingReviewController::class, 'index'])->name('admin.reviewList');
            //     Route::get('/edit/{id}', [RatingReviewController::class, 'editReview'])->name('admin.editReview');
            //     Route::post('/update/{id}', [RatingReviewController::class, 'updateReview'])->name('admin.updateReview');
            // });
            // // Route::get('/pending_review', [RatingReviewController::class, 'changeStatus'])->name('admin.reviewChangeStatus');
            // });
            // #admin stores/gallery  route
            // Route::prefix('gallery')->group(function () {
            //     Route::get('/', [GalleryController::class, 'galleryIndex'])->name('admin.galleryItemList')->middleware('checkLicense');
            //     Route::post('/create', [GalleryController::class, 'galleryStore'])->name('admin.storeGalleryItem')->middleware('checkLicense');
            //     Route::delete('/delete', [GalleryController::class, 'galleryDestroy'])->name('admin.galleryDestroy')->middleware('checkLicense');
            // });
        });

        Route::group(['prefix' => 'settings',], function () {
            #list type route
            Route::get('list-type', [LockupTypeController::class, 'index'])->name('admin.indexListType');
            Route::get('list-type/{id}', [LockupTypeController::class, 'show'])->name('admin.showListType');
            Route::post('list-item/store', [LockupController::class, 'store'])->name('admin.storeListItem');
            Route::post('list-item/update/{id}', [LockupController::class, 'update'])->name('admin.updateListItem');
            Route::post('list-item/destroy/{id}', [LockupController::class, 'destroy'])->name('admin.destroyListItem');
            #configuration route
            Route::group(['prefix' => 'configuration',], function () {
                Route::get('/index', [ConfigurationController::class, 'index'])->name('admin.indexConfiguration');
                Route::post('/currency-setting', [ConfigurationController::class, 'currencyStore'])->name('admin.currencyStoreConfiguration');
                Route::post('/store-time-schedule', [ConfigurationController::class, 'storeStoreTimeSchedule'])->name('admin.storeTimeSchedule');
                Route::post('/store-payment-method', [ConfigurationController::class, 'paymentMethodStore'])->name('admin.paymentMethodStoreConfiguration');
                Route::post('/store-goole-map-api-key', [ConfigurationController::class, 'storeGoogleMapApiKey'])->name('admin.storeGoogleMapApiKey');
                Route::post('/store-product-location-radius', [ConfigurationController::class, 'storeProductLocationRadius'])->name('admin.storeProductLocationRadius');
            });
        });
    }
);
