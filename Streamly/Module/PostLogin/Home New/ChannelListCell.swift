//
//  ChannelListCell.swift
//  Streamly
//
//  Created by Rohit Singh on 7/3/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class ChannelListCell: UITableViewCell {

    // MARK: - Properties
    static let reuseIdentifier = "channelListCell"


    // MARK: - Outlets
    @IBOutlet weak var channelNo      : UILabel!
    @IBOutlet weak var ChannelName    : UILabel!
    @IBOutlet weak var logoImage      : UIImageView!

    override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)

        // Configure the view for the selected state
    }

    // MARK: - Helpers
    func configureCell(_ logo:String, _ channelName:String,item:Int)
    {
        self.channelNo.text = "\(item + 1)"
        self.ChannelName.text = channelName
        self.logoImage.sd_setImage(with: URL(string: logo), placeholderImage: UIImage(named: "Logo2"))
        // self.imageLogo.image = UIImage(named: logo)

    }

}
