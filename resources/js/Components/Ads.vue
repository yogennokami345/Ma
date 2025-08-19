<template>
  <div ref="container" id="ads"></div>
</template>

<script setup>
import { onMounted, watch, ref, nextTick } from 'vue'

const props = defineProps({
  html: {
    type: String,
    required: true
  }
})

const container = ref(null)

const renderHtmlWithScripts = async () => {
  if (!container.value) return

  container.value.innerHTML = props.html

  await nextTick()

  const scripts = container.value.querySelectorAll('script')
  scripts.forEach(oldScript => {
    const newScript = document.createElement('script')

    for (const attr of oldScript.attributes) {
      newScript.setAttribute(attr.name, attr.value)
    }

    newScript.textContent = oldScript.textContent

    oldScript.parentNode.replaceChild(newScript, oldScript)
  })
}

onMounted(renderHtmlWithScripts)
watch(() => props.html, renderHtmlWithScripts)
</script>
