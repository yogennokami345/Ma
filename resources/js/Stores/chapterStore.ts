import { defineStore } from 'pinia';

interface ReadChapter {
  chapter_path: string;
  chapter_number: number;
  comic_id: string;
}

interface ReadChapters {
  [comicId: string]: number[];
}
//@ts-ignore
export const useChapterStore = defineStore('chapterStore', {
  state: () => ({
    lastReadChapter: null as ReadChapter | null,
    readChapters: {} as ReadChapters,
  }),
  
  actions: {
    setLastReadChapter(chapter: ReadChapter) {
      this.lastReadChapter = chapter;
      
      if (!this.readChapters[chapter.comic_id]) {
        this.readChapters[chapter.comic_id] = [];
      }
      
      if (!this.readChapters[chapter.comic_id].includes(chapter.chapter_number)) {
        this.readChapters[chapter.comic_id].push(chapter.chapter_number);
      }
    },
    
    isChapterRead(comicId: string, chapterNumber: number): boolean {
      return this.readChapters[comicId]?.includes(chapterNumber) || false;
    },
    
    removeChapterFromHistory(comicId: string, chapterNumber: number) {
      if (this.readChapters[comicId]) {
        this.readChapters[comicId] = this.readChapters[comicId].filter(num => num !== chapterNumber);
      }
    }
  },
  persist: true,
});