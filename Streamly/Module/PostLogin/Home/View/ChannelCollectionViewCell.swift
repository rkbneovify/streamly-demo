//
//  ChannelCollectionViewCell.swift
//  Streamly
//
//  Created by Rohit Singh on 5/19/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit
import SDWebImage

class ChannelCollectionViewCell: UICollectionViewCell {
    
    @IBOutlet weak var containerView: UIView!
    @IBOutlet weak var imageView: UIImageView!

    @IBOutlet weak var channelName: UILabel!
    @IBOutlet weak var channelNumber: UILabel!
    override func awakeFromNib() {
        super.awakeFromNib()
        self.setup()
        // These properties are also exposed in Interface Builder.
        //               imageView.adjustsImageWhenAncestorFocused = true
        //               imageView.clipsToBounds = false
        // Initialization code
    }

    required init?(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder)
     //   self.setup()
    }

    override init(frame: CGRect) {
        super.init(frame: frame)
        self.setup()
    }

    func setup() {
         self.containerView.backgroundColor = UIColor.init(red: 89/255, green: 97/255, blue: 100/255, alpha: 1.0)
        
    }

    func configureWith(channel: ChannelDetails)
    {
        self.containerView.cornerRadius = 8
        self.containerView.clipsToBounds = true
        if let _ = channel.logo {
        self.imageView.sd_setImage(with: URL(string: channel.logo ?? ""), placeholderImage: UIImage(named: "Logo2"))
        }
        self.channelName.text = channel.CallSign ?? ""
        self.channelNumber.text = "\(channel.id ?? 0)"
    }



    override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        coordinator.addCoordinatedAnimations({
            if !self.isFocused {
                self.containerView.backgroundColor = UIColor.init(red: 89/255, green: 97/255, blue: 100/255, alpha: 1.0)
                //    self.backgroundColor = UIColor.gray
            }
            else {
                self.containerView.backgroundColor = UIColor.lightGray
            }
        }, completion: nil)
    }
}
