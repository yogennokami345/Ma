<template>
    <Transition
      name="slide-ad"
      @enter="onEnter"
      @leave="onLeave"
    >
      <div
        v-if="isVisible"
        class="fixed bottom-6 right-6 w-80 h-48 bg-zinc-50 border-2 border-dashed border-zinc-300 rounded-lg z-[9999] transform transition-all duration-300 ease-out"
      >
        <div
          @click="closeAd"
          class="absolute -top-3 -right-3 w-8 h-8 bg-zinc-800 hover:bg-zinc-900 rounded-lg shadow-lg cursor-pointer flex items-center justify-center transition-all duration-200 hover:shadow-xl hover:scale-105"
          role="button"
          tabindex="0"
          aria-label="Close advertisement"
          @keydown.enter="closeAd"
          @keydown.space="closeAd"
        >
          <X class="w-4 h-4 text-zinc-100" />
        </div>

        <div class="w-full h-full flex flex-col items-center justify-center p-6 text-zinc-600">
          <div class="w-full h-full bg-zinc-100 rounded-md border border-zinc-200 flex flex-col items-center justify-center space-y-3">
            <!-- <div class="w-12 h-12 bg-zinc-200 rounded-full flex items-center justify-center">
              <Play class="w-6 h-6 text-zinc-500 ml-0.5" />
            </div> -->

            <!-- <div class="text-center space-y-1">
              <p class="text-sm font-medium text-zinc-700">Video Advertisement</p>
              <p class="text-xs text-zinc-500">Content will appear here</p>
            </div> -->
            <div>
                <slot />
            </div>
            <!-- <div class="flex space-x-1">
              <div class="w-2 h-2 bg-zinc-400 rounded-full animate-bounce"></div>
              <div class="w-2 h-2 bg-zinc-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
              <div class="w-2 h-2 bg-zinc-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
            </div> -->
          </div>
        </div>
      </div>
    </Transition>
  </template>

  <script setup lang="ts">
  import { ref, onMounted, readonly } from 'vue'
  import { X, Play } from 'lucide-vue-next'

  // Props interface
  interface SimpleVideoAdProps {
    delayMs?: number
    autoShow?: boolean
  }

  // Props with defaults
  const props = withDefaults(defineProps<SimpleVideoAdProps>(), {
    delayMs: 2000,
    autoShow: true
  })

  // Reactive state
  const isVisible = ref(false)

  // Methods
  const closeAd = (): void => {
    isVisible.value = false
  }

  const showAd = (): void => {
    // Check if ad was recently closed (within last 30 minutes)
    const lastClosed = localStorage.getItem('simpleVideoAdClosed')
    const now = Date.now()

    if (lastClosed && (now - parseInt(lastClosed)) < 30 * 60 * 1000) {
      console.log('Ad recently closed, not showing')
      return
    }

    setTimeout(() => {
      isVisible.value = true
    }, props.delayMs)
  }

  // Animation event handlers
  const onEnter = (el: Element): void => {
    console.log('Simple video ad entering')
  }

  const onLeave = (el: Element): void => {
    console.log('Simple video ad leaving')
  }

  // Lifecycle
  onMounted(() => {
    if (props.autoShow) {
      showAd()
    }
  })

  // Expose methods for external control
  defineExpose({
    showAd,
    closeAd,
    isVisible: readonly(isVisible)
  })
  </script>

  <style scoped>
  /* Entrance and exit animations */
  .slide-ad-enter-active {
    transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
  }

  .slide-ad-leave-active {
    transition: all 0.4s cubic-bezier(0.55, 0.06, 0.68, 0.19);
  }

  .slide-ad-enter-from {
    transform: translateX(calc(100% + 2rem)) translateY(1rem) scale(0.8);
    opacity: 0;
  }

  .slide-ad-leave-to {
    transform: translateX(calc(100% + 2rem)) translateY(1rem) scale(0.8);
    opacity: 0;
  }

  /* Hover effects */
  .fixed:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  }

  .fixed:hover .border-dashed {
    border-color: theme('colors.zinc.400');
  }

  /* Responsive design */
  @media (max-width: 640px) {
    .fixed {
      width: calc(100vw - 3rem);
      height: 160px;
      right: 1.5rem;
      bottom: 1.5rem;
    }
  }

  @media (max-width: 480px) {
    .fixed {
      width: calc(100vw - 2rem);
      height: 140px;
      right: 1rem;
      bottom: 1rem;
    }
  }

  /* Custom bounce animation timing */
  @keyframes bounce {
    0%, 80%, 100% {
      transform: scale(0);
    }
    40% {
      transform: scale(1);
    }
  }

  .animate-bounce {
    animation: bounce 1.4s infinite ease-in-out both;
  }
  </style>
