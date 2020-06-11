//
//  PlayerVC.swift
//  Streamly
//
//  Created by Rohit Singh on 5/27/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

import AVKit

class PlayerVC: UIViewController, AVPlayerViewControllerDelegate {



    // MARK: LifeCycle
    override func viewDidLoad() {
        super.viewDidLoad()
        self.setupPlayer()
    }

    // MARK: Helpers
    private func setupPlayer()
    {
        let mediaURL = URL(string: "http://qthttp.apple.com.edgesuite.net/1010qwoeiuryfg/sl.m3u8")!
        let asset = AVAsset(url: mediaURL)

        /*
         Create an `AVPlayerItem` for the `AVAsset` and populate it with information
         about the video.
         */
        let playerItem = AVPlayerItem(asset: asset)
        playerItem.externalMetadata = sampleExternalMetaData()
        playerItem.navigationMarkerGroups = [sampleNavigationMarkerGroup()]
        playerItem.interstitialTimeRanges = sampleInterstitialTimeRanges()

        // Create and present an `AVPlayerViewController`.
        let playerViewController = AVPlayerViewController()
        playerViewController.delegate = self

        let player = AVPlayer(playerItem: playerItem)
        playerViewController.player = player

        self.present(playerViewController, animated: true) {
            /*
             Begin playing the media as soon as the controller has
             been presented.
             */
            player.play()
        }
    }

    // MARK: AVPlayerViewControllerDelegate

    func playerViewController(_ playerViewController: AVPlayerViewController, willPresent interstitial: AVInterstitialTimeRange) {
        // Prevent the user from skipping intersitials.
        playerViewController.requiresLinearPlayback = true
    }

    func playerViewController(_ playerViewController: AVPlayerViewController, didPresent interstitial: AVInterstitialTimeRange) {
        /*
         Allow the user to navigate anywhere in the video once an interstitial
         has been played.
         */
        playerViewController.requiresLinearPlayback = false
    }

    // MARK: Meta data

    func sampleExternalMetaData() -> [AVMetadataItem] {
        let titleItem = AVMutableMetadataItem()
        titleItem.identifier = AVMetadataIdentifier.commonIdentifierTitle
        titleItem.value = "Apple Special Event, September 2015" as NSString
        titleItem.extendedLanguageTag = "und"

        let descriptionItem = AVMutableMetadataItem()
        descriptionItem.identifier = AVMetadataIdentifier.commonIdentifierDescription
        descriptionItem.value = "Check out iPhone 6s and iPhone 6s Plus, learn about the powerful iPad Pro, take a look at the new features and bands for Apple Watch, and see the premiere of the all-new Apple TV." as NSString
        descriptionItem.extendedLanguageTag = "und"

        return [titleItem, descriptionItem]
    }

    func sampleNavigationMarkerGroup() -> AVNavigationMarkersGroup {
        let group = AVNavigationMarkersGroup(title: "Announcements", timedNavigationMarkers: [
            timedMetaDataGroupWithTitle("Apple Watch", startTime: 90, endTime: 917),
            timedMetaDataGroupWithTitle("iPad Pro", startTime: 917, endTime: 1691),
            timedMetaDataGroupWithTitle("Apple Pencil", startTime: 1691, endTime: 3105),
            timedMetaDataGroupWithTitle("Apple TV", startTime: 3105, endTime: 4968),
            timedMetaDataGroupWithTitle("iPhone", startTime: 4968, endTime: 7328)
        ])

        return group
    }

    func sampleInterstitialTimeRanges() -> [AVInterstitialTimeRange] {
        let ranges = [
            AVInterstitialTimeRange(timeRange: timeRange(withStartTime: 728, duration: 53)),
            AVInterstitialTimeRange(timeRange: timeRange(withStartTime: 1015, duration: 55)),
            AVInterstitialTimeRange(timeRange: timeRange(withStartTime: 3305, duration: 759)),
            AVInterstitialTimeRange(timeRange: timeRange(withStartTime: 7261, duration: 61))
        ]

        return ranges
    }

    // MARK: Convenience

    func timedMetaDataGroupWithTitle(_ title: String, startTime: TimeInterval, endTime: TimeInterval) -> AVTimedMetadataGroup {
        // Create an `AVMetadataItem` for the title.
        let titleItem = AVMutableMetadataItem()
        titleItem.identifier = AVMetadataIdentifier.commonIdentifierTitle
        titleItem.value = title as NSString
        titleItem.extendedLanguageTag = "und"

        // Create a `CMTimeRange` from the supplied information.
        let range = timeRange(from: startTime, to: endTime)

        return AVTimedMetadataGroup(items: [titleItem], timeRange: range)
    }

    func timeRange(from startTime: TimeInterval, to endTime: TimeInterval) -> CMTimeRange {
        let cmStartTime = CMTimeMakeWithSeconds(Float64(startTime), preferredTimescale: 100)
        let cmEndTime = CMTimeMakeWithSeconds(Float64(endTime), preferredTimescale: 100)
        let timeRange = CMTimeRangeFromTimeToTime(start: cmStartTime, end: cmEndTime)

        return timeRange
    }
    
    func timeRange(withStartTime startTime: TimeInterval, duration: TimeInterval) -> CMTimeRange {
        let cmStartTime = CMTimeMakeWithSeconds(Float64(startTime), preferredTimescale: 100)
        let cmDuration = CMTimeMakeWithSeconds(Float64(duration), preferredTimescale: 100)
        let timeRange = CMTimeRange(start: cmStartTime, duration: cmDuration)

        return timeRange
    }
}
