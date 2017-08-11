package com.example.lime.myapplication


import android.content.Intent
import android.os.Bundle

import com.google.android.youtube.player.YouTubeBaseActivity
import com.google.android.youtube.player.YouTubeInitializationResult
import com.google.android.youtube.player.YouTubePlayer
import kotlinx.android.synthetic.main.new_file.*
import org.jetbrains.anko.toast

class Newscr : YouTubeBaseActivity(), YouTubePlayer.OnInitializedListener {
    internal var DEVELOPER_KEY = "AIzaSyAgKicAArIGzEX47QMTz-VqqfG07_l4LAU"
    var a=""
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.new_file)


        old.setOnClickListener {
            val myIntent = Intent(this@Newscr, MainActivity::class.java)
            startActivity(myIntent)
            finish()
        }

        a="ZEhN2vb2Ngw"
        youtube_view.initialize(DEVELOPER_KEY, this)

    }

    override fun onInitializationFailure(provider: YouTubePlayer.Provider,
                                         errorReason: YouTubeInitializationResult) {
        if (errorReason.isUserRecoverableError) {
            errorReason.getErrorDialog(this, RECOVERY_DIALOG_REQUEST).show()
        } else {

            toast("error")
        }
    }

    override fun onInitializationSuccess(provider: YouTubePlayer.Provider,
                                         player: YouTubePlayer, wasRestored: Boolean) {
        if (!wasRestored) {
            player.fullscreenControlFlags = YouTubePlayer.FULLSCREEN_FLAG_CONTROL_ORIENTATION
            player.addFullscreenControlFlag(YouTubePlayer.FULLSCREEN_FLAG_ALWAYS_FULLSCREEN_IN_LANDSCAPE)
            player.loadVideo(a)
            player.setPlayerStyle(YouTubePlayer.PlayerStyle.DEFAULT)
        }
    }

    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent) {
        if (requestCode == RECOVERY_DIALOG_REQUEST) {

            youTubePlayerProvider.initialize(DEVELOPER_KEY, this)
        }
    }

    private val youTubePlayerProvider: YouTubePlayer.Provider
        get() = youtube_view

    companion object {

        private val RECOVERY_DIALOG_REQUEST = 1
    }
}
