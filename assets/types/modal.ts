import type { ModalInterface } from 'flowbite/dist/flowbite.turbo';

export interface ModalEventDetails {
  modal: ModalInterface | null;
}

export type ModalHideEvent = CustomEvent<ModalEventDetails>;

export type ModalShowEvent = CustomEvent<ModalEventDetails>;

export type ModalToggleEvent = CustomEvent<ModalEventDetails>;
