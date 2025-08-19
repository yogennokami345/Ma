import { defineStore } from 'pinia';

// @ts-ignore
export const useUserStore = defineStore('user', {
  state: () => ({
    images: {} as Record<string, string>, // Armazena banner, avatar, etc.
  }),
  actions: {
    setImage(key: string, url: string) {
      this.images = { ...this.images, [key]: url }; // Garante reatividade
    },
    getImage(key: string, defaultUrl: string): string {
      return this.images[key] || defaultUrl;
    },
  },
  persist: true, // Ativa persistência automática
});
