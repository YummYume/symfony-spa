import type { DismissInterface } from 'flowbite';

export interface DismissEventDetails {
  dismiss: DismissInterface | null;
}

export type DismissHideEvent = CustomEvent<DismissEventDetails>;
