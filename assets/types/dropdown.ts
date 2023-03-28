import type { DropdownInterface } from 'flowbite';

export interface DropdownEventDetails {
  dropdown: DropdownInterface;
}

export type DropdownHideEvent = CustomEvent<DropdownEventDetails>;

export type DropdownShowEvent = CustomEvent<DropdownEventDetails>;

export type DropdownToggleEvent = CustomEvent<DropdownEventDetails>;
