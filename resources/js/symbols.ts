import type { InjectionKey } from 'vue'
import type { SpinnerInject } from '@/types'

export const SpinnerKey: InjectionKey<SpinnerInject> = Symbol('Spinner')
export const PageSpinnerKey: InjectionKey<SpinnerInject> = Symbol('PageSpinner')
