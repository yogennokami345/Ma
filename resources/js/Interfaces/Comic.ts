interface Comic {
  id: number;
  banner: string;
  cover: string;
  title: string;
  alternative_name: string;
  description: string;
  views: number;
  is_adult: boolean;
  slug: string;
  release_date: string;
  statuses: Status[];
  genres: Genre[];
  chapters: Chapter[];
  created_at: string;
  updated_at: string;
}
