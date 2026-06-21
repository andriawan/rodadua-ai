import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import BaseButton from '../../components/BaseButton.vue'

describe('BaseButton', () => {
  it('renders default button', () => {
    const wrapper = mount(BaseButton, {
      slots: { default: 'Click me' },
    })
    expect(wrapper.text()).toContain('Click me')
    expect(wrapper.find('button').exists()).toBe(true)
  })

  it('shows loading spinner when loading', () => {
    const wrapper = mount(BaseButton, {
      props: { loading: true },
      slots: { default: 'Save' },
    })
    expect(wrapper.find('svg.animate-spin').exists()).toBe(true)
    expect(wrapper.find('button').attributes('disabled')).toBeDefined()
  })

  it('emits click event', () => {
    const wrapper = mount(BaseButton, {
      slots: { default: 'Click' },
    })
    wrapper.find('button').trigger('click')
    expect(wrapper.emitted('click')).toHaveLength(1)
  })

  it('does not emit click when disabled', () => {
    const wrapper = mount(BaseButton, {
      props: { disabled: true },
      slots: { default: 'Click' },
    })
    wrapper.find('button').trigger('click')
    expect(wrapper.emitted('click')).toBeUndefined()
  })

  it('applies variant classes', () => {
    const wrapper = mount(BaseButton, {
      props: { variant: 'danger' },
      slots: { default: 'Delete' },
    })
    expect(wrapper.classes()).toEqual(
      expect.arrayContaining(['bg-danger-500'])
    )
  })
})
