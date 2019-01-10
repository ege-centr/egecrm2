<template>
  <div>
    <TestList :add-button='true' />
  </div>
</template>

<script>
import { TestList } from '@/components/Test'

export default {
  components: { TestList },
}
</script>