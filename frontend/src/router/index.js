import Vue from 'vue'
import VueRouter from 'vue-router'
import store from "../store/index.js"
 let token = localStorage.getItem("token")

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: "Home",
    component: () => import('@/views/dashboard/Dashboard.vue'),
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){ 
        next()
      }else{
        next("/login")
      }
    }
  },

  {
    path: '/dashboard',
    component: () => import('@/views/dashboard/Dashboard.vue'),
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){ 
         next() 
       }else{
         next("/login")
       }

     }
  },
  
  {
    path: '/profile',
    component: () => import('@/views/pages/Profile/Profile.vue'),
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){ 
         next() 
       }else{
         next("/login")
       }
 
     }
  },

  {
    path: '/login',
    component: () => import('@/views/pages/Auth/Login.vue'),
    meta: {
      layout: 'blank',
    },
     beforeEnter: (to, from, next) => {
        
       if(token){
         next("/dashboard")
       }else{
         next()
       }

     }
      
  },
 
  {
    path: '/forgot-password',
    component: () => import('@/views/pages/Auth/ForgotPassword/Forgot Password.vue'),

  },
  {
    path: '/code-confimation',
    component: () => import('@/views/pages/Auth/ForgotPassword/Check Code.vue'),

  },
  {
    path: '/reset-password',
    component: () => import('@/views/pages/Auth/ForgotPassword/Reset Password.vue'),

  },

  {
    path: '/users-management',
    component: () => import('@/views/pages/Admin/Auth/Users/Users Management.vue'),
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  }, 
  {
    path: '/trash-users-management',
    component: () => import('@/views/pages/Admin/Auth/Users/Trash Users Management'),
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  }, 
  {
    path: '/roles-management',
    component: () => import('@/views/pages/Admin/Auth/Roles/Roles Management.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  },
  {
    path: '/trash-roles-management',
    component: () => import('@/views/pages/Admin/Auth/Roles/Trash Roles Management.vue'),
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  }, 
  {
    path: '/countries-management',
    component: () => import('@/views/pages/Admin/Geocodes/Countries/Countries Management.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){ 
        next()
      }else{
        next("/login")
      }
    }
  },
  {
    path: '/trash-countries-management',
    component: () => import('@/views/pages/Admin/Geocodes/Countries/Trash Countries Management.vue'),
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  },
  {
    path: '/cities-management',
    component: () => import('@/views/pages/Admin/Geocodes/Cities/Cities Management.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){ 
        next()
      }else{
        next("/login")
      }
    }
  },
  {
    path: '/trash-cities-management',
    component: () => import('@/views/pages/Admin/Geocodes/Cities/Trash Cities Management.vue'),
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  },

  {
    path: '/towns-management',
    component: () => import('@/views/pages/Admin/Geocodes/Towns/Towns Management.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){ 
        next()
      }else{
        next("/login")
      }
    }
  },
  {
    path: '/trash-towns-management',
    component: () => import('@/views/pages/Admin/Geocodes/Towns/Trash Towns Management.vue'),
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    } 
  },
  {
    path: '/banners-management',
    component: () => import('@/views/pages/Admin/Banners/Banners Management.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){ 
        next()
      }else{
        next("/login")
      }
    }
  },
  {
    path: '/trash-banners-management',
    component: () => import('@/views/pages/Admin/Banners/Trash Banners  Management.vue'),
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  },


  {
    path: '/push-notifications-management',
    component: () => import('@/views/pages/Admin/PushNotifications/PushNotifications Management.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){ 
        next()
      }else{
        next("/login")
      }
    }
  },
  {
    path: '/users-push-notification/:pushnotification_id',
    component: () => import('@/views/pages/Admin/PushNotifications/Users PushNotification.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){ 
        next()
      }else{
        next("/login")
      }
    }
  },
  {
    path: '/create-push-notification',
    component: () => import('@/views/pages/Admin/PushNotifications/Create PushNotification.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){ 
        next()
      }else{
        next("/login")
      }
    }
  },


  {
    path: '/main-categories-management',
    component: () => import('@/views/pages/Admin/Main Categories/Main Categories Management.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  },
  {
    path: '/trash-main-categories-management',
    component: () => import('@/views/pages/Admin/Main Categories/Trash Main Categories Management.vue'),
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  },
     {
    path: '/sub-categories-management/',
    component: () => import('@/views/pages/Admin/Sub Categories/Sub Categories Management.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  }, 
  {
    path: '/trash-sub-categories-management',
    component: () => import('@/views/pages/Admin/Sub Categories/Trash Sub Categories Management.vue'),
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  }, 
  {
    path: '/second-sub-categories-management',
    component: () => import('@/views/pages/Admin/Second Sub Categories/Second Sub Categories Management.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  },
  {
    path: '/trash-second-sub-categories-management',
    component: () => import('@/views/pages/Admin/Second Sub Categories/Trash Second Sub Categories Management.vue'),
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  }, 

  { 
    path: '/products-management',
    component: () => import('@/views/pages/Admin/Products/Products Management.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  }, 
   { 
    path: '/create-product',
    component: () => import('@/views/pages/Admin/Products/Create Product.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  },

    { 
    path: '/upsells-management',
    component: () => import('@/views/pages/Admin/UpSells/UpSells Management.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  }, 
   { 
    path: '/trash-upsells-management',
    component: () => import('@/views/pages/Admin/UpSells/Trash UpSells Management.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  }, 
   { 
    path: '/create-upsell',
    component: () => import('@/views/pages/Admin/UpSells/Create UpSell.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  },
    { 
    path: '/edit-upsells-product/:id/:product_id',
    component: () => import('@/views/pages/Admin/UpSells/Edit UpSell.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  },
  {
    path: '/coupons-management',
    component: () => import('@/views/pages/Admin/Coupons/Coupons Management.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){ 
        next()
      }else{
        next("/login")
      }
    }
  },
  {
    path: '/trash-coupons-management',
    component: () => import('@/views/pages/Admin/Coupons/Trash Coupons Management.vue'),
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  },
  {
    path: '/orders-management',
    component: () => import('@/views/pages/Admin/Orders/Orders Management.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){ 
        next()
      }else{
        next("/login")
      }
    }
  },
  {
    path: '/trash-orders-management',
    component: () => import('@/views/pages/Admin/Orders/Trash Orders Management.vue'),
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  },

  {
    path: '/rules-management',
    component: () => import('@/views/pages/Admin/Rules/Rules Management.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){ 
        next()
      }else{
        next("/login")
      }
    }
  },
  {
    path: '/trash-rules-management',
    component: () => import('@/views/pages/Admin/Rules/Trash Rules Management.vue'),
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  },
  {
    path: '/new-message',
    component: () => import('@/views/pages/Admin/Chats/New Message.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){ 
        next()
      }else{
        next("/login")
      }
    }
  },
    {
    path: '/chats-recieved-management',
    component: () => import('@/views/pages/Admin/Chats/Chats Recieved/Chats Management.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){ 
        next()
      }else{
        next("/login")
      }
    }
  },
  {
    path: '/chats-recieved-management',
    component: () => import('@/views/pages/Admin/Chats/Chats Recieved/Chats Management.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){ 
        next()
      }else{
        next("/login")
      }
    }
  },
  {
    path: '/trash-chats-recieved-management',
    component: () => import('@/views/pages/Admin/Chats/Chats Recieved/Trash Chats Management.vue'),
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  },

  {
    path: '/chats-sended-management',
    component: () => import('@/views/pages/Admin/Chats/Chats Sended/Chats Management.vue'),
    
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){ 
        next()
      }else{
        next("/login")
      }
    }
  },
  {
    path: '/trash-chats-sended-management',
    component: () => import('@/views/pages/Admin/Chats/Chats Sended/Trash Chats Management.vue'),
     beforeEnter: (to, from, next) => { 
 let token = store.state.token

        
       if(token){
         next()
       }else{
         next("/login")
       }
     }
  },
    {
    path: '/views-management',
    component: () => import('@/views/pages/Admin/Views/Views Management.vue'),
    
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){ 
         next()
       }else{
         next("/login")
       }
     }
  },
  {
    path: '/trash-views-management',
    component: () => import('@/views/pages/Admin/Views/Trash Views Management.vue'),
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){
         next()
       }else{
         next("/login")
       }
     }
  },
    {
    path: '/movements-management',
    component: () => import('@/views/pages/Admin/Movements/Movements Management.vue'),
    
     beforeEnter: (to, from, next) => { 
 let token = store.state.token

        
       if(token){ 
         next()
       }else{
         next("/login")
       }
     }
  },
  {
    path: '/trash-movements-management',
    component: () => import('@/views/pages/Admin/Movements/Trash Movements Management.vue'),
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){
         next()
       }else{
         next("/login")
       }
     }
  },
 
  {
    path: '/wallet-system-management',
    component: () => import('@/views/pages/Admin/BuyingSystemMount/BuyingSystemMount Management.vue'),
    
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){ 
         next()
       }else{
         next("/login")
       }
     }
  }, 
  {
    path: '/wallets-management',
    component: () => import('@/views/pages/Admin/Wallets/Wallets Management.vue'),
    
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){ 
         next()
       }else{
         next("/login")
       }
     }
  },


  {
    path: '/searches-management',
    component: () => import('@/views/pages/Admin/Searches/Searches Management.vue'),
    
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){ 
         next()
       }else{
         next("/login")
       }
     }
  },
  {
    path: '/trash-searches-management',
    component: () => import('@/views/pages/Admin/Searches/Trash Searches Management.vue'),
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  },

  {
    path: '/payments-management',
    component: () => import('@/views/pages/Admin/Payments/Payments Management.vue'),
    
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){ 
         next()
       }else{
         next("/login")
       }
     }
  },
  {
    path: '/trash-payments-management',
    component: () => import('@/views/pages/Admin/Payments/Trash Payments Management.vue'),
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  },
    {
    path: '/services-management',
    component: () => import('@/views/pages/Admin/Services/Services Management.vue'),
    
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){ 
         next()
       }else{
         next("/login")
       }
     }
  }, 
  {
    path: '/trash-services-management',
    component: () => import('@/views/pages/Admin/Services/Trash Services Management.vue'),
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  },


  {
    path: '/questions-management',
    component: () => import('@/views/pages/Admin/Questions/Questions Management.vue'),
    
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){ 
         next()
       }else{
         next("/login")
       }
     }
  }, 
  {
    path: '/trash-questions-management',
    component: () => import('@/views/pages/Admin/Questions/Trash Questions Management.vue'),
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  },

  {
    path: '/question-categories-management',
    component: () => import('@/views/pages/Admin/Questions/Categories/Questions Management.vue'),
    
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){ 
         next()
       }else{
         next("/login")
       }
     }
  }, 
  {
    path: '/trash-question-categories-management',
    component: () => import('@/views/pages/Admin/Questions/Categories/Trash Questions Management.vue'),
    beforeEnter: (to, from, next) => {
let token = store.state.token

       
      if(token){
        next()
      }else{
        next("/login")
      }
    }
  },



  { 
    path: '/edit-product/:id',
    component: () => import('@/views/pages/Admin/Products/Edit Product.vue'),
    
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){
         next()
       }else{
         next("/login")
       }
     }
  },
  {
    path: '/trash-products-management',
    component: () => import('@/views/pages/Admin/Products/Trash Products Management.vue'),
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){
         next()
       }else{
         next("/login")
       }
     }
  }, 
 
  
 
 {
    path: '/favorites-management',
    component: () => import('@/views/pages/Admin/Favorites/Favorites Management.vue'),
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){
         next()
       }else{
         next("/login")
       }
     }
  }, 
    {
    path: '/trash-favorites-management',
    component: () => import('@/views/pages/Admin/Favorites/Trash Favorites Management.vue'),
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){
         next()
       }else{
         next("/login")
       }
     }
  }, 
  {
    path: '/reviews-management',
    component: () => import('@/views/pages/Admin/Reviews/Reviews Management.vue'),
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){
         next()
       }else{
         next("/login")
       }
     }
  }, 
    {
    path: '/trash-reviews-management',
    component: () => import('@/views/pages/Admin/Reviews/Trash Reviews Management.vue'),
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){
         next()
       }else{
         next("/login")
       }
     }
  }, 
  {
    path: '/system-reviews-management',
    component: () => import('@/views/pages/Admin/System Reviews/System Review Management.vue'),
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){
         next()
       }else{
         next("/login")
       }
     }
  }, 
    {
    path: '/trash-system-reviews-management',
    component: () => import('@/views/pages/Admin/System Reviews/Trash System Review Management.vue'),
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){
         next()
       }else{
         next("/login")
       }
     }
  }, 

  {
    path: '/system-review-types-management',
    component: () => import('@/views/pages/Admin/System Reviews/Types/System Review Types Management.vue'),
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){
         next()
       }else{
         next("/login")
       }
     }
  }, 
    {
    path: '/trash-system-review-types-management',
    component: () => import('@/views/pages/Admin/System Reviews/Types/Trash System Review Types Management.vue'),
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){
         next()
       }else{
         next("/login")
       }
     }
  }, 


  {
    path: '/carts-management',
    component: () => import('@/views/pages/Admin/Carts/Carts Management.vue'),
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){
         next()
       }else{
         next("/login")
       }
     }
  }, 
    {
    path: '/products-cart/:cart_id',
    component: () => import('@/views/pages/Admin/Carts/ProductArrayAttributes Cart.vue'),
    
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){ 
         next()
       }else{
         next("/login")
       }
     }
  },
    {
    path: '/trash-carts-management',
    component: () => import('@/views/pages/Admin/Carts/Trash Carts Management.vue'),
     beforeEnter: (to, from, next) => {
 let token = store.state.token

        
       if(token){
         next()
       }else{
         next("/login")
       }
     }
  }, 

  {
    path: '/error-404',
    name: 'error-404',
    component: () => import('@/views/Error.vue'),
    meta: {
      layout: 'blank',
    },
  },
  {
    path: '*',
    redirect: 'error-404',
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes,
})

export default router
