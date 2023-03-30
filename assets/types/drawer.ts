import type { DrawerInterface } from 'flowbite/lib/esm';

export interface DrawerEventDetails {
  drawer: DrawerInterface;
}

export type DrawerHideEvent = CustomEvent<DrawerEventDetails>;

export type DrawerShowEvent = CustomEvent<DrawerEventDetails>;

export type DrawerToggleEvent = CustomEvent<DrawerEventDetails>;
