//
//  ChannelViewModel.swift
//  Streamly
//
//  Created by Rohit Singh on 5/12/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//


import UIKit

class ChannelViewModel {
  var channel: Channel
  var channelNumberText: String
  var nameText: String
  var logo: String?

  init(channel: Channel) {
    self.channel = channel
    self.channelNumberText = "Ch \(channel.number)"
    self.nameText = "Ch.\(channel.number)"
    self.logo = channel.logo
  }
}
