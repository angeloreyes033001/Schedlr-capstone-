<template>
    <div class="t-bg-logoBlue t-w-full t-h-full p-3 t-select-none t-grid t-grid-rows-[1fr,80px]" >
        <div class=" t-h-full"  >
            <div class="t-mt-[1px]" v-for="nav in navigations" >
                <div v-if="nav.children.length" >
                    <div @click="sidebarOpenChildren(nav.title)" class="t-text-white hover:t-text-logoYellow t-grid t-grid-cols-[50px,1fr,30px] t-h-[40px] t-rounded-[10px] t-cursor-pointer" >
                        <span class="t-flex t-justify-center t-items-center" >
                            <fa :icon="nav.icon" ></fa>
                        </span>
                        <span class="t-flex t-items-center" >
                            <label class="t-capitalize" >{{ nav.title }}</label>
                        </span>
                        <span class="t-flex t-justify-center t-items-center" >
                            <fa icon="caret-down" ></fa>
                        </span>
                    </div>
                    <div v-show="isSelectedChild === nav.title " :class="{' t-duration-150 ':isSelectedChild === nav.title}" class=" t-bg-blue-400 t-ml-[20px] t-mr-[20px] t-pt-[10px] t-pb-[10px] t-pl-[20px] t-pr-[20px] t-rounded-[10px] t-mt-[5px]" >
                        <div v-for="child in nav.children" >
                            <div class=" t-h-[40px]" >
                                <router-link  class="t-text-white t-h-full hover:t-text-logoYellow text-decoration-none" :to="child.path" >
                                    <div class="t-grid t-grid-cols-[40px,1fr] t-h-full" >
                                        <div class="t-flex t-justify-center t-items-center" >
                                            <fa :icon="child.icon" ></fa>
                                        </div>
                                        <div class="t-flex t-items-center" >
                                            <label class="t-capitalize" >{{ child.title }}</label>
                                        </div>
                                    </div>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else >
                    <div class=" t-h-[40px] t-rounded-[10px] t-cursor-pointer" >
                        <router-link :to="nav.path" class="t-text-white hover:t-text-logoYellow text-decoration-none" >
                            <div class="t-grid t-grid-cols-[50px,1fr] t-h-full" >
                                <div class="t-flex t-justify-center t-items-center" >
                                    <fa :icon="nav.icon" ></fa>
                                </div>
                                <div class="t-flex t-items-center" >
                                    <label class="t-capitalize" >{{ nav.title }}</label>
                                </div>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
        <div class="" >
            <div class="t-h-[40px]  t-flex t-items-center" >
                <router-link :to="settingLink" class="text-decoration-none t-text-white hover:t-text-logoYellow" >
                    <div class="t-grid t-grid-cols-[50px,1fr]" >
                        <div class="t-flex t-justify-center t-items-center" >
                            <fa icon="cog" ></fa>
                        </div>
                        <div class="t-flex t-items-center" >
                            <label class="t-capitalize" >Setting</label>
                        </div>
                    </div>
                </router-link>
            </div>
            <div @click="logout" class="t-h-[40px] t-text-white hover:t-text-logoYellow t-grid t-grid-cols-[50px,1fr]" >
                <div class="t-flex t-justify-center t-items-center" >
                    <fa icon="sign-out" ></fa>
                </div>
                <div class="t-flex t-items-center" >
                    <label class="t-capitalize" >Logout</label>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup >
import { ref,defineProps } from 'vue';
import { useRouter } from 'vue-router';
const router = useRouter();
const logout = ()=>{
    localStorage.clear();
    router.push('/login')
}

    const isSelectedChild = ref("");

    const props = defineProps({
        navigations: {
            type: Array,
            required: true,
        },
        settingLink: {
            type: String,
            required: true
        }
    });
    const sidebarOpenChildren = (title)=>{
        if(isSelectedChild.value != title){
            isSelectedChild.value = title;
        }
        else{
            isSelectedChild.value = "";
        }    
    }
</script>