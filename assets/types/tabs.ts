import type { TabsInterface } from 'flowbite/dist/flowbite.turbo';

export interface TabsEventDetails {
  tabs: TabsInterface | null;
}

export type TabsShowEvent = CustomEvent<TabsEventDetails>;
