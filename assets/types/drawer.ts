import type { DrawerInterface } from 'flowbite';

export interface DrawerEventDetails {
  drawer: DrawerInterface;
}

export type DrawerHideEvent = CustomEvent<DrawerEventDetails>;

export type DrawerShowEvent = CustomEvent<DrawerEventDetails>;

export type DrawerToggleEvent = CustomEvent<DrawerEventDetails>;
