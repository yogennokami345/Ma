<script setup lang="ts">
import { Icon } from '@iconify/vue';
import { Button } from '../button';
import { Drawer, DrawerClose, DrawerContent, DrawerDescription, DrawerFooter, DrawerHeader, DrawerTitle, DrawerTrigger } from '../drawer';
import { computed, onMounted } from 'vue';
import Comment from '../../Comment/index.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import TextEditor from '@/Components/TextEditor/index.vue';
import { useForm } from '@inertiajs/vue3';
import route from 'ziggy-js';
import { toast } from 'vue3-toastify'

const props = defineProps<{ chapter: Chapter, comments: Array<any> }>();
onMounted(
  () => {
    // @ts-ignore
    function remove_disqus_ads() { let e = document.querySelector("#disqus_thread"); if (e) { let t = e.querySelector("iframe"); t && (t.contentDocument || t.contentWindow.document).querySelectorAll(".mn-thumb").forEach(e => { e.parentNode.removeChild(e) }) } } function is_disqus_ads() { let e = document.querySelector("#disqus_thread"); if (null === e) return !1; let t = e.children; return t.length >= 4 && (e.removeChild(t[0]), !0) } document.addEventListener("DOMContentLoaded", () => { is_disqus_ads() && remove_disqus_ads(), setInterval(() => { is_disqus_ads() && remove_disqus_ads() }, 1e3) });
  }
)

function sendComment(text: string) {
  const form = useForm({
    content: text,
  });
  form.post(route('chapter.comment', {id: props.chapter.chapter_path}), {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Comentário enviado com sucesso');
    },
    onError: () => {
      toast.error('Erro ao enviar comentário');
    }
  });
}
</script>

<template>
  <div>
    <Drawer class="max-h-56">
      <DrawerTrigger>
        <button class="p-2 rounded-xl group hover:bg-primary-foreground transition-colors">
          <Icon icon="prime:comments" width="30" height="30"
            class="group-hover:opacity-70 transition-colors opacity-50" />
        </button>
      </DrawerTrigger>
      <DrawerContent>
        <div class="flex flex-col gap-10 container max-w-screen-2xl max-h-[80vh] overflow-y-auto">
          <div>
            <DrawerHeader class="flex items-center justify-center">
              <DrawerTitle>Comentarios</DrawerTitle>
              <!-- <DrawerDescription>This action cannot be undone.</DrawerDescription> -->
            </DrawerHeader>
            <TextEditor @send="sendComment" />
          </div>
          <EmptyState v-if="comments.length == 0" mood="sad" title="Em Breve" description="Logo teremos comentários" />
          <div class="flex flex-col gap-3">
            <div v-for="(comment, index) in comments" :key="index">
              <Comment :comment="comment" />
            </div>
          </div>
          <DrawerFooter>
            <!-- <Button>Submit</Button>
                <DrawerClose>
                  <Button variant="outline">
                    Cancel
                  </Button>
                </DrawerClose> -->
          </DrawerFooter>
        </div>
      </DrawerContent>
    </Drawer>
  </div>
</template>

<style scoped></style>