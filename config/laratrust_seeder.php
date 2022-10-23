<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => true,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'superadministrator' => [
            'users' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'roles' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'profile' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'countries' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'cities' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'towns' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            
         'banners' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'categories' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
           'buying_system_mounts' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
           'carts' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'chats' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'coupons' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'products' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'product_attributes' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'favorites' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'reviews' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
           'views' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
             'rewards' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
           'movements' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'orders' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'payments' => 'r,t*,res*,r-a*,sh*,c,s*,e*,u,d,f-d*',
             'rules' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'searches' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'services' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'similar_products' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'system_reviews' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
                        'system_review_types' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'push_notifications' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
           'up_sells' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'wallets' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',


        ],
        'administrator' => [
            'profile' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'countries' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'cities' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'towns' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            
                        
         'banners' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'categories' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
           'buying_system_mounts' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
           'carts' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'chats' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'coupons' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'products' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'product_attributes' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'favorites' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'reviews' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
           'views' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
             'rewards' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
           'movements' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'orders' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'payments' => 'r,t*,res*,r-a*,sh*,c,s*,e*,u,d,f-d*',
             'rules' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'searches' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'services' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'similar_products' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'system_reviews' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'system_review_types' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'push_notifications' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
           'up_sells' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'wallets' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'system_reviews' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',
            'wallets' => 'r,t,res,r-a,sh,c,s,e,u,d,f-d',

        ],
        'user' => [
            'profile' => 'r,u,s,req,u-p,sh',
            'chats'=>'g,a',
            'rooms'=>'g',
            'coupons'=>'g,re,a',
            'favorites'=>'g,re,a',
            'mevements'=>'g,a',
            'orders'=>'sh-user-address , a , a-address , sh-address , rese, co , g-address , u-address , d-address , fin, g-coupon-order , g , g-my-orders , g-my-orders-status , sh-my-order , a-review-order , sh-review-order ',
           

            'reviews'=>'a',
            
            'rewards'=>'g',
            
            'rules'=>'sh',
            
            'searches'=>'la,d,d-a',
            
            'reviews'=>'g,a',
            

            'wallets'=>'a,fini,b'
            
        ]
    ],

    'permissions_map' => [
        'r' => 'read',
        't' => 'trash',
        'res' => 'restore',
        'r-a' => 'restore-all',
        'sh' => 'show',
        'c' => 'create',
        's' => 'store',
        'e' => 'edit',
        'u' => 'update',
        'u' => 'update',
        'd' => 'destroy',
        'f-d' => 'force-destroy',
        
        'g'=>'get',
        'se'=>'select',
        'a'=>'add',
        're'=>'remove',
        
        'sh-user-address'=>'show-data-user-address',
        'a-address'=>'add-address',
        'sh-address'=>'show-address',
        'rese'=>'resend',
        'co'=>'code',
        'g-address'=>'get-address',
        'u-address'=>'update-address',
        'd-address'=>'delete-address',
        'fini'=>'finish',
        'g-coupon-order'=>'get-coupon-order',
        'g-my-orders'=>'get-my-orders',
        'g-my-orders-status'=>'get-my-orders-status',
        'sh-my-order'=>'show-my-order',
        'a-review-order'=>'add-review-order',
        'sh-review-order'=>'show-review-order',
        
        'sea'=>'search',
        'sho'=>'show-attr',
        
        'req'=>'request',
        
        'acc'=>'accept',
        
        'u-p'=>'update-password',
        
        'la'=>'last',
        'd-a'=>'delete-all',
        
        'b'=>'balance',
        
        
    ]
];
