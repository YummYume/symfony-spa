import type { DismissInterface } from 'flowbite';

export interface DismissEventDetails {
  dismiss: DismissInterface;
}

export type DismissHideEvent = CustomEvent<DismissEventDetails>;
