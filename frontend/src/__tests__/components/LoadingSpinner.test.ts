import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import LoadingSpinner from '../../components/LoadingSpinner.vue'

describe('LoadingSpinner', () => {
  it('renders with default label', () => {
    const wrapper = mount(LoadingSpinner)
    expect(wrapper.text()).toContain('Loading...')
  })

  it('renders with custom label', () => {
    const wrapper = mount(LoadingSpinner, {
      props: { label: 'Memuat data...' },
    })
    expect(wrapper.text()).toContain('Memuat data...')
  })

  it('applies overlay mode', () => {
    const wrapper = mount(LoadingSpinner, {
      props: { overlay: true },
    })
    expect(wrapper.classes()).toEqual(
      expect.arrayContaining(['fixed', 'inset-0'])
    )
  })

  it('applies sm size class', () => {
    const wrapper = mount(LoadingSpinner, {
      props: { size: 'sm' },
    })
    const spinner = wrapper.find('.animate-spin')
    expect(spinner.classes()).toEqual(
      expect.arrayContaining(['h-6', 'w-6'])
    )
  })
})
