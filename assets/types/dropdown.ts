import type { DropdownInterface } from 'flowbite/dist/flowbite.turbo';

export interface DropdownEventDetails {
  dropdown: DropdownInterface | null;
}

export type DropdownHideEvent = CustomEvent<DropdownEventDetails>;

export type DropdownShowEvent = CustomEvent<DropdownEventDetails>;

export type DropdownToggleEvent = CustomEvent<DropdownEventDetails>;
