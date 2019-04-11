<template>
  <div>
    <div class='headline mb-5'>DEV1</div>

    <Timeline />

    <!-- <AddBtn label='Добавить' animated /> -->
    <!-- <div class='outer'>
      <div class='inner'>
      </div>
    </div> -->
  </div>
</template>



<script>

import Timeline from '@/components/UI/Timeline'

export default {
  components: { Timeline },
  
  data() {
    return {
     
    }
  },

 
}
</script>


<style scoped lang='scss'>
  .outer {
    background: PaleVioletRed;
    width: 200px;
    height: 50px;
    position: relative;
    border-radius: 40px;
    overflow: hidden;
    & .inner {
      // background: Turquoise;
      background: white;
      position: absolute;
      left: 0;
      height: 100%;
      width: 100%;
    }
  }
</style>
