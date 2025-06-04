<script setup>
import Header from "@/components/navigation/Header.vue";
import Footer from "@/components/navigation/Footer.vue";

</script>
  <template>
    <div class="min-h-screen flex flex-col ">
      <Header/>
      <main class="flex-grow">
        <router-view />
      </main>
      <Footer/>
    </div>
  </template>
<style scoped>

</style>
