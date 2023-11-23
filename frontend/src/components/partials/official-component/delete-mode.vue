<template>
    <div class="t-flex" >
        <div  v-if="props.officialList.monday.list.length > 0 || 
        props.officialList.tuesday.list.length > 0 || 
        props.officialList.wednesday.list.length > 0 || 
        props.officialList.thursday.list.length > 0 ||
        props.officialList.friday.list.length > 0 || 
        props.officialList.saturday.list.length > 0 " class="table-holder t-w-[200px]" v-for="(day,indexDay) in officialList" >
            <div class="t-bg-logoBlue t-h-[50px]" >
                <div class="t-pt-[15px] t-pl-[10px] t-font-bold t-text-white t-truncate t-capitalize" >{{indexDay}}</div>
            </div>
            <div class="" >
                <div v-for="list in day.list" class="t-mt-[10px] t-font-semibold p-2" >
                    <div class="t-h-auto] t-grid t-bg-slate-100 t-shadow t-w-full t-rounded-[10px] t-p-2" >
                        <div>
                            <span class="t-font-semibold" >{{ list.time }}</span>
                        </div>
                        <div class="t-mt-2" >
                            <button @click="delete_official_time(list.id)" class="btn btn-danger t-w-full" >
                                <fa icon="trash" ></fa>
                                &nbsp;
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class=" t-justify-center t-items-center t-w-full p-5 t-grid" >
            <img class="t-w-[500px] t-border-2 t-border-white t-border-b-slate-300" src="../../../assets/images/no-data.svg" alt="no-data">
            <h6 class="text-center t-capitalize t-mt-2" > no record found</h6>
        </div>
    </div>
</template>
<script setup>

const props = defineProps({
    officialList: {
        type: Object,
        required: true
    }
})

const emit = defineEmits(['delete_official_time']);

const delete_official_time = (id) =>{

    emit('delete_official_time',id)

}

</script>