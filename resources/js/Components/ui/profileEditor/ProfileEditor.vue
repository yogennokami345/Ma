<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { toast } from 'vue3-toastify';
import route from 'ziggy-js';
import NumberFlow, { NumberFlowGroup } from '@number-flow/vue'

interface ProfileEditorProps {
  initialBio: string
  initialProfilePic: string
  initialBanner: string
}

const props = withDefaults(defineProps<ProfileEditorProps>(), {
  initialBio: '',
  initialProfilePic: '',
  initialBanner: ''
});


const bio = ref(props.initialBio);
const profilePreview = ref(props.initialProfilePic);
const bannerPreview = ref(props.initialBanner);
const profileFile = ref(null);
const bannerFile = ref(null);

//@ts-ignore
const handleProfileUpload = (event) => {
  const file = event.target.files[0];
  if (!file) return;
  
  if (!file.type.match('image.*')) {
    alert('Por favor, selecione uma imagem válida');
    return;
  }
  
  profileFile.value = file;
  form.cover = file;

  const reader = new FileReader();
  reader.onload = (e) => {
    profilePreview.value = e.target!.result as string;
  };
  reader.readAsDataURL(file);

};

//@ts-ignore
const handleBannerUpload = (event) => {
  const file = event.target.files[0];
  if (!file) return;

  if (!file.type.startsWith('image/')) { // Melhor validação
    toast.error('Selecione um arquivo de imagem válido');
    return;
  }

  bannerFile.value = file;
  form.banner = file;


  const reader = new FileReader();
  reader.onload = (e) => {
    bannerPreview.value = e.target!.result as string;
  };
  reader.readAsDataURL(file);
};
//@ts-ignore
const removeProfile = (event) => {
  event.stopPropagation();
  profilePreview.value = '';
  profileFile.value = null;
};

//@ts-ignore
const removeBanner = (event) => {
  event.stopPropagation();
  bannerPreview.value = '';
  bannerFile.value = null;
};


const form = useForm({
  description: bio.value,
  cover: null,
  banner:null
})

console.log(form.cover)

const uploadData = () => {
  form.post(route('updateProfile'), {
    preserveScroll: true,
    onSuccess: (response) => {
      toast.success('Perfil Atualizdo Com Sucesso', { theme: 'dark', dangerouslyHTMLString: true, position: 'bottom-right', autoClose: 3000 });
      location.reload();
      // router
    },
    onError: (err) => {
      if(err.banner){
        toast.error(err.banner)
      }
      if(err.cover){
        toast.error(err.cover)
      }
      if(err.description){
        toast.error(err.description)
      }
    }
  })
}

const formHasContent = computed(() => {
  return form.description.trim() !== '' || 
         form.cover !== null || 
         form.banner !== null;
});
</script>

<template>
  <div class="bg-gray-50 dark:bg-[#12161a] rounded-lg p-4 max-w-2xl mx-auto">
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        Banner Image
      </label>
      <div 
        class="relative h-32 bg-gray-200 dark:bg-gray-800 rounded-lg overflow-hidden flex items-center justify-center transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 group"
        :class="{ 'border-2 border-dashed border-gray-400 hover:border-blue-500': !bannerPreview }"
      >
        <img 
          v-if="bannerPreview" 
          :src="bannerPreview" 
          class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
          alt="Banner preview"
        />
        <div v-else class="text-center p-4 transition-all duration-300 transform group-hover:scale-110">
          <div class="text-gray-500 dark:text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-8 w-8 transition-all duration-300 group-hover:text-blue-500 animate-pulse-slow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="mt-1 text-sm transition-all duration-300 group-hover:text-blue-500">Arraste uma imagem ou clique para selecionar</p>
          </div>
        </div>
        <input 
          type="file" 
          @change="handleBannerUpload" 
          accept="image/*"
          class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
        />
        <button 
          v-if="bannerPreview" 
          @click.stop="removeBanner" 
          class="absolute top-2 right-2 bg-gray-800 bg-opacity-70 text-white rounded-full p-1 hover:bg-opacity-100 transition-all duration-300 hover:rotate-90 transform hover:scale-110"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Profile Picture Upload -->
    <div class="mb-4 flex items-start">
      <div class="mr-4">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Foto de Perfil
        </label>
        <div 
          class="relative w-24 h-24 bg-gray-200 dark:bg-gray-800 rounded-full overflow-hidden flex items-center justify-center transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 group"
          :class="{ 'border-2 border-dashed border-gray-400 hover:border-blue-500': !profilePreview }"
        >
          <img 
            v-if="profilePreview" 
            :src="profilePreview" 
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
            alt="Profile preview"
          />
          <div v-else class="text-center transition-all duration-300 transform group-hover:scale-110">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-8 w-8 text-gray-500 dark:text-gray-400 transition-all duration-1000 group-hover:text-blue-500 animate-bounce-slow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
          <input 
            type="file" 
            @change="handleProfileUpload" 
            accept="image/*"
            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
          />
          <button 
            v-if="profilePreview" 
            @click.stop="removeProfile" 
            class="absolute top-0 right-0 bg-gray-800 bg-opacity-70 text-white rounded-full p-1 hover:bg-opacity-100 transition-all duration-300 hover:rotate-90 transform hover:scale-110"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Bio Text Area -->
      <div class="flex-1">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Biografia
        </label>
        <div class="relative group">
          <textarea 
            v-model="form.description" 
            class="w-full px-3 py-2 text-gray-700 dark:text-gray-200 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-800 dark:border-gray-700 resize-none transition-all duration-300 group-hover:border-blue-400 group-hover:shadow-md"
            rows="4"
            maxlength="160"
            placeholder="Conte um pouco sobre você..."
          ></textarea>
          <div class="absolute bottom-2 right-2">
            <NumberFlowGroup class="text-xs text-gray-500 dark:text-gray-400 transition-all duration-300 group-hover:text-blue-500">
                <div style="--number-flow-char-height: 0.85em" class="flex items-center">
                    <NumberFlow :value="form.description.length "/><span>/150</span>
                </div>
            </NumberFlowGroup>
          </div>
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end space-x-2 mt-4">
      <button 
        @click="uploadData"
        :disabled="!formHasContent" 
        class="px-4 py-2 rounded-md transition-all duration-300 flex items-center"
        :class="formHasContent 
          ? 'bg-blue-600 text-white hover:bg-blue-700 active:scale-95' 
          : 'bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed'"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        Salvar
      </button>
    </div>
  </div>
</template>


<style>
/* Animações personalizadas */
@keyframes pulse-slow {
  0%, 100% {
    opacity: 1;
    transform: scale(1);
  }
  50% {
    opacity: 0.9;
    transform: scale(1.03);
  }
}

@keyframes bounce-slow {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-3px);
  }
}

.animate-pulse-slow {
  animation: pulse-slow 5s ease-in-out infinite;
}

.animate-bounce-slow {
  animation: bounce-slow 4s ease-in-out infinite;
}

.group:hover .absolute.inset-0 {
  background-color: rgba(59, 130, 246, 0.2);
  transition: background-color 0.3s ease;
}
</style>