<template>
    <div class="mt-5" >
        <div class="t-bg-logoBlue t-relative t-h-[30px] t-grid t-grid-cols-8 t-text-center" >
            <div class="bg-white shadow t-h-[50px] t-w-[20px]  t-relative t-top-[-30px] " style="border-radius: 5px;" ></div>
            <div class="bg-white shadow t-h-[50px] t-w-[20px]  t-relative t-top-[-30px] " style="border-radius: 5px;" ></div>
            <div class="bg-white shadow t-h-[50px] t-w-[20px]  t-relative t-top-[-30px] " style="border-radius: 5px;" ></div>
            <div class="bg-white shadow t-h-[50px] t-w-[20px]  t-relative t-top-[-30px] " style="border-radius: 5px;" ></div>
            <div class="bg-white shadow t-h-[50px] t-w-[20px]  t-relative t-top-[-30px] " style="border-radius: 5px;" ></div>
            <div class="bg-white shadow t-h-[50px] t-w-[20px]  t-relative t-top-[-30px] " style="border-radius: 5px;" ></div>
            <div class="bg-white shadow t-h-[50px] t-w-[20px]  t-relative t-top-[-30px] " style="border-radius: 5px;" ></div>
            <div class="bg-white shadow t-h-[50px] t-w-[20px]  t-relative t-top-[-30px] " style="border-radius: 5px;" ></div>
            <div class="bg-white shadow t-h-[50px] t-w-[20px]  t-absolute t-top-[-30px]  t-right-0" style="border-radius: 5px;" ></div>
        </div>
        <div class=" t-bg-logoBlue t-grid t-grid-cols-7 t-h-[50px]" >
            <span class="d-flex align-items-end text-uppercase fw-bold text-white text-nowrap p-1 " >Time</span>
            <span class="d-flex align-items-end text-uppercase fw-bold text-white text-nowrap p-1 " >Mon</span>
            <span class="d-flex align-items-end text-uppercase fw-bold text-white text-nowrap p-1 " >Tue</span>
            <span class="d-flex align-items-end text-uppercase fw-bold text-white text-nowrap p-1 " >Wed</span>
            <span class="d-flex align-items-end text-uppercase fw-bold text-white text-nowrap p-1 " >Thu</span>
            <span class="d-flex align-items-end text-uppercase fw-bold text-white text-nowrap p-1 " >Fri</span>
            <span class="d-flex align-items-end text-uppercase fw-bold text-white text-nowrap p-1 " >Sat</span>
        </div>
        <div class="t-h-[600px] shadow t-overflow-x-auto" >
            <div class="t-grid t-grid-cols-7 bg-white shadow"  v-for="official in 30" :key="official" >
                <span class="text-uppercase fw-thin text-nowrap p-1 t-h-[30px] t-text-[#6D6F88]" >time</span>
                <span class="text-uppercase fw-thin text-nowrap p-1 t-h-[30px] t-text-[#6D6F88]" >sche</span>
                <span class="text-uppercase fw-thin text-nowrap p-1 t-h-[30px] t-text-[#6D6F88]" >sche</span>
                <span class="text-uppercase fw-thin text-nowrap p-1 t-h-[30px] t-text-[#6D6F88]" >sche</span>
                <span class="text-uppercase fw-thin text-nowrap p-1 t-h-[30px] t-text-[#6D6F88]" >sche</span>
                <span class="text-uppercase fw-thin text-nowrap p-1 t-h-[30px] t-text-[#6D6F88]" >sche</span>
                <span class="text-uppercase fw-thin text-nowrap p-1 t-h-[30px] t-text-[#6D6F88]" >sche</span>
            </div>
        </div>
    </div>
</template>
<script setup >
    
</script>