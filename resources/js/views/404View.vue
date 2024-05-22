<template>
  <div class="flex flex-col items-center justify-center gap-2">
    <!-- <h1>404</h1> -->
    <div class="flex flex-col gap-2">
      <p>Page is not found :(</p>
      <button @click="$router.back()" class="button py-1">
          Go back
      </button>
    </div>
  </div>
</template>
