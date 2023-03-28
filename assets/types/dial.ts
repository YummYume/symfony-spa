import type { DialInterface } from 'flowbite/lib/esm';

export interface DialEventDetails {
  dial: DialInterface;
}

export type DialHideEvent = CustomEvent<DialEventDetails>;

export type DialShowEvent = CustomEvent<DialEventDetails>;

export type DialToggleEvent = CustomEvent<DialEventDetails>;
