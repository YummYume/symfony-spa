import type { PopoverInterface } from 'flowbite';

export interface PopoverEventDetails {
  popover: PopoverInterface;
}

export type PopoverHideEvent = CustomEvent<PopoverEventDetails>;

export type PopoverShowEvent = CustomEvent<PopoverEventDetails>;

export type PopoverToggleEvent = CustomEvent<PopoverEventDetails>;
