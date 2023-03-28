import type { DrawerInterface } from 'flowbite/dist/flowbite.turbo';

export interface DrawerEventDetails {
  drawer: DrawerInterface | null;
}

export type DrawerHideEvent = CustomEvent<DrawerEventDetails>;

export type DrawerShowEvent = CustomEvent<DrawerEventDetails>;

export type DrawerToggleEvent = CustomEvent<DrawerEventDetails>;
