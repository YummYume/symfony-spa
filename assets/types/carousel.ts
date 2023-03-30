import type { CarouselInterface } from 'flowbite/lib/esm';

export interface CarouselEventDetails {
  carousel: CarouselInterface;
}

export type CarouselNextEvent = CustomEvent<CarouselEventDetails>;

export type CarouselPrevEvent = CustomEvent<CarouselEventDetails>;

export type CarouselChangeEvent = CustomEvent<CarouselEventDetails>;
