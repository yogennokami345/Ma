import { defineStore } from 'pinia';

// @ts-ignore
export const useReaderSettings = defineStore('readerSettings', {
  state: () => ({
    brightness: [100],
    contrast: [100],
    saturation: [100],
    sepia: [0],
    hueRotate: [0],
    negative: false,
    greyscale: false,
    fixed: false,
  }),
  actions: {
    resetSettings() {
      this.brightness = [100];
      this.contrast = [100];
      this.saturation = [100];
      this.sepia = [0];
      this.hueRotate = [0];
      this.negative = false;
      this.greyscale = false;
      this.fixed = false;
    },
    setNegative(value: boolean) {
      this.negative = value;
    },
    setGreyscale(value: boolean) {
      this.greyscale = value;
    },
  },
  persist: true,
});