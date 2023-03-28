import type { CarouselInterface } from 'flowbite';

export interface CarouselEventDetails {
  carousel: CarouselInterface | null;
}

export type CarouselNextEvent = CustomEvent<CarouselEventDetails>;

export type CarouselPrevEvent = CustomEvent<CarouselEventDetails>;

export type CarouselChangeEvent = CustomEvent<CarouselEventDetails>;
