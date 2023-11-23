<template>
    <div class="t-h-screen t-w-screen t-grid t-justify-center t-items-center" >
        <div class="text-center" >
            <img style="height: 200px;" src="../assets/images/404 logo.png" alt="">
            <h2 class="text-center t-text-logoYellow p-0 m-0" >Sorry, Page not found</h2>
            <p class="text-center t-text-[12px] t-text-logoYellow" >Sorry, Page not found Looks like the page you're looking for is either missing,<br>doesn't exist, or can't reach at the moment</p>
            <div>
                <router-link to="/"  class="btn bg-warning text-white t-rounded-[20px]" >
                    Go to Homepage
                </router-link>
            </div>
        </div>
    </div>
</template>