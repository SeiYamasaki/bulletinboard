<template>
  <div class="container mt-4">
    <h2>新規投稿</h2>
    <form @submit.prevent="submitPost">
      <div class="mb-3">
        <label for="title" class="form-label">タイトル</label>
        <input v-model="title" id="title" class="form-control" placeholder="タイトル" required />
      </div>

      <div class="mb-3">
        <label for="content" class="form-label">内容</label>
        <textarea v-model="content" id="content" class="form-control" placeholder="内容" required></textarea>
      </div>

      <button type="submit" class="btn btn-primary">投稿</button>
    </form>
  </div>
</template>

<script>
import { ref } from "vue";

export default {
  setup() {
    const title = ref("");
    const content = ref("");

    const submitPost = () => {
      console.log("投稿:", { title: title.value, content: content.value });
      // ここで API に送信する処理を追加
    };

    return { title, content, submitPost };
  }
};
</script>
