import type { DialInterface } from 'flowbite/dist/flowbite.turbo';

export interface DialEventDetails {
  dial: DialInterface | null;
}

export type DialHideEvent = CustomEvent<DialEventDetails>;

export type DialShowEvent = CustomEvent<DialEventDetails>;

export type DialToggleEvent = CustomEvent<DialEventDetails>;
