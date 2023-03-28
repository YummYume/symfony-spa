import type { DropdownInterface } from 'flowbite';

export interface DropdownEventDetails {
  dropdown: DropdownInterface | null;
}

export type DropdownHideEvent = CustomEvent<DropdownEventDetails>;

export type DropdownShowEvent = CustomEvent<DropdownEventDetails>;

export type DropdownToggleEvent = CustomEvent<DropdownEventDetails>;
