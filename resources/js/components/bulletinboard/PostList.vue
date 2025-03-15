<template>
  <div class="container mt-4">
    <h2>掲示板一覧</h2>

    <ul class="list-group">
      <li v-for="post in posts" :key="post.id" class="list-group-item">
        <h3>{{ post.title }}</h3>
        <p>{{ post.content }}</p> <!-- ← 本文を追加 -->
        <p><strong>いいね:</strong> {{ post.likes_count }}</p>

        <button @click="likePost(post.id)" class="btn btn-outline-primary">
          👍 いいね
        </button>
      </li>
    </ul>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// WebSocket 設定
window.Pusher = Pusher;
window.Echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1',
  encrypted: true
});

export default {
  setup() {
    const posts = ref([]);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'); // CSRF トークンを取得

    /**
     * 投稿一覧を取得
     */
    const fetchPosts = async () => {
      try {
        console.log("🔄 fetchPosts() 実行中...");
        const response = await fetch('/bulletinboard/posts');

        if (!response.ok) {
          throw new Error(`HTTPエラー: ${response.status}`);
        }

        const data = await response.json();
        console.log("✅ 取得したデータ:", data);
        posts.value = data;
      } catch (error) {
        console.error("❌ 投稿データの取得に失敗:", error);
      }
    };

    /**
     * いいねボタンの処理
     */
    const likePost = async (postId) => {
      try {
        console.log(`👍 いいねリクエスト送信: postId=${postId}`);

        const response = await fetch(`/bulletinboard/posts/${postId}/like`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken // CSRFトークンを追加！
          },
        });

        if (!response.ok) {
          throw new Error(`HTTPエラー: ${response.status}`);
        }

        const data = await response.json();
        console.log("👍 いいね更新レスポンス:", data);

        // いいね数を即時更新
        const updatedPost = posts.value.find(post => post.id === postId);
        if (updatedPost) {
          updatedPost.likes_count = data.likes_count;
        }
      } catch (error) {
        console.error("❌ いいね処理に失敗:", error);
      }
    };

    /**
     * WebSocket のリスナーを設定
     */
    const subscribeToLikesChannel = () => {
      try {
        console.log("📡 WebSocket: 'likes' チャンネルに接続");
        window.Echo.channel('likes').listen('LikeUpdated', (event) => {
          console.log("📡 WebSocket: いいねが更新されました:", event);

          // 受信したデータを Vue のデータに反映
          const updatedPost = posts.value.find(post => post.id === event.post_id);
          if (updatedPost) {
            updatedPost.likes_count = event.likes_count;
          }
        });
      } catch (error) {
        console.error("❌ WebSocket の購読に失敗:", error);
      }
    };

    /**
     * コンポーネントがマウントされたときの処理
     */
    onMounted(() => {
      fetchPosts();
      subscribeToLikesChannel();
    });

    /**
     * コンポーネントがアンマウントされたときの処理（メモリリーク防止）
     */
    onUnmounted(() => {
      try {
        console.log("🛑 WebSocket: 'likes' チャンネルを解除");
        window.Echo.leaveChannel('likes');
      } catch (error) {
        console.error("❌ WebSocket の解除に失敗:", error);
      }
    });

    return { posts, likePost };
  }
};
</script>
