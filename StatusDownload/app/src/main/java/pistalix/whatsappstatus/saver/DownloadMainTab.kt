package pistalix.whatsappstatus.saver

import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.support.v4.app.Fragment
import android.support.v4.app.FragmentManager
import android.support.v4.view.ViewPager
import android.support.v4.app.FragmentStatePagerAdapter
import it.neokree.materialtabs.MaterialTab
import it.neokree.materialtabs.MaterialTabListener
import kotlinx.android.synthetic.main.activity_download_main_tab.*


class DownloadMainTab : AppCompatActivity(), MaterialTabListener {

    lateinit var adapter: ViewPagerAdapter

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main_tab)
        overridePendingTransition(R.xml.enter, R.xml.exit);

        adapter = ViewPagerAdapter(supportFragmentManager);
        pager.adapter = adapter;

        pager.setOnPageChangeListener(object : ViewPager.SimpleOnPageChangeListener() {
            override fun onPageSelected(position: Int) {
                // when user do a swipe the selected tab change
                tabHost.setSelectedNavigationItem(position)

            }
        })

        for (i in 0 until adapter.getCount()) {
            tabHost.addTab(
                    tabHost.newTab()
                            .setText(adapter.getPageTitle(i))
                            .setTabListener(this)
            )

        }

    }

    inner class ViewPagerAdapter(fm: FragmentManager) : FragmentStatePagerAdapter(fm) {

        override fun getItem(num: Int): Fragment {
            if(num == 1){
                return DownloadFragmentVideos()

            }else{
                return DownloadBlankFragment()
            }

        }

        override fun getCount(): Int {
            return 2
        }

        override fun getPageTitle(position: Int): CharSequence {
            if(position == 0){

                return "Images"
            }else{

                return "Videos"
            }

        }

    }
    override fun onTabReselected(tab: MaterialTab?) {

    }

    override fun onTabUnselected(tab: MaterialTab?) {

    }

    override fun onTabSelected(tab: MaterialTab?) {
        if (tab != null) {
            pager.currentItem = tab.position
        }
    }

    override fun onBackPressed() {
        finish()
        overridePendingTransition(R.xml.nathing, R.xml.exit)

    }

}