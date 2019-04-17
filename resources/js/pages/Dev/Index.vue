<template>
  <div>
    <div class='headline mb-5'>DEV1</div>

    <!-- <AddBtn label='Добавить' animated /> -->
    <!-- <div class='outer'>
      <div class='inner'>
      </div>
    </div> -->
  </div>
</template>



<script>


export default {
  components: {  },
  
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
