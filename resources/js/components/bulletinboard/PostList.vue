<template>
  <div class="container mt-4">
    <h2>掲示板一覧</h2>
    <button @click="fetchPosts" class="btn btn-primary mb-3">🔄 最新の投稿を取得</button>

    <ul class="list-group">
      <li v-for="post in posts" :key="post.id" class="list-group-item">
        <h3>{{ post.title }}</h3>
        <p>{{ post.content }}</p>
        <a :href="'/bulletinboard/posts/' + post.id" class="btn btn-secondary">詳細を見る</a>
      </li>
    </ul>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';

export default {
  setup() {
    const posts = ref([]);

    const fetchPosts = async () => {
      try {
        const response = await fetch('/bulletinboard/posts');
        posts.value = await response.json();
      } catch (error) {
        console.error("投稿データの取得に失敗しました:", error);
      }
    };

    onMounted(fetchPosts);

    return { posts, fetchPosts };
  }
};
</script>
