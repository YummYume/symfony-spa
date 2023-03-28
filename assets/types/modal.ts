import type { ModalInterface } from 'flowbite';

export interface ModalEventDetails {
  modal: ModalInterface;
}

export type ModalHideEvent = CustomEvent<ModalEventDetails>;

export type ModalShowEvent = CustomEvent<ModalEventDetails>;

export type ModalToggleEvent = CustomEvent<ModalEventDetails>;
