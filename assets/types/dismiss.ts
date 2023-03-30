import type { DismissInterface } from 'flowbite/lib/esm';

export interface DismissEventDetails {
  dismiss: DismissInterface;
}

export type DismissHideEvent = CustomEvent<DismissEventDetails>;
