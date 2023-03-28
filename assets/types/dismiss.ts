import type { DismissInterface } from 'flowbite/dist/flowbite.turbo';

export interface DismissEventDetails {
  dismiss: DismissInterface | null;
}

export type DismissHideEvent = CustomEvent<DismissEventDetails>;
