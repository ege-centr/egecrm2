<template>
  <div>
    <h1>DEV</h1>
    <LoadingChip ref='LoadingChip' :file='file' :dev='dev' />
    <hr />
    <input style='margin: 10px; padding: 5px; border: 1px solid black' type="number" v-model='file.percentComplete'>
    <div>
      <label>loading</label>
      <input type="checkbox" v-model='dev.loading' style='margin-top: 30px'>
    </div>
  </div>
</template>

<script>

import LoadingChip from '@/components/UI/LoadingChip'

export default {
  components: { LoadingChip },
  data() {
    return {
      file: {
        name: 'some_file_name.jpg',
        state: 'progress',
        percentComplete: 99,
      },
      dev: {
        loading: false,
      },
    }
  },

  watch: {
    file: {
      deep: true,
      handler(newVal) {
        console.log('here', newVal)
        if (newVal.percentComplete >= 100) {
          if (newVal.state !== 'success') {
            this.file.state = 'success'
          }
        } else {
          this.file.state = 'progress'
        }
      }
    } 
  }
}
</script>
